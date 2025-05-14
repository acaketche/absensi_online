<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeesController extends Controller
{
    // Menampilkan daftar employee
    public function index()
    {
    $roles = Role::all();
    $positions = Position::all(); // kalau ada posisi juga
    $employees = Employee::all(); // misal untuk list pegawai
    return view('employee.index', compact('roles', 'positions', 'employees'));
    }

    // Menampilkan form tambah employee dengan daftar posisi
    public function create()
    {
        $roles = Role::all();
        $positions = Position::all(); // Ambil semua posisi dari database
        return view('employee.create', compact('positions','roles')); // Kirim data posisi ke view
    }

    public function show($id)
{
    $employee = Employee::with(['role', 'position'])->where('id_employee', $id)->first();

    if (!$employee) {
        return response()->json(['error' => 'Pegawai tidak ditemukan'], 404);
    }

    return response()->json($employee);
}

    // Menyimpan data employee baru
    public function store(Request $request)
    {
        $request->validate([
            'id_employee' => 'required|unique:employees,id_employee',
            'fullname' => 'required|string|max:100',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email',
            'role_id' => 'nullable|integer',
            'position_id' => 'nullable|integer',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qr_code' => 'nullable|image|mimes:png|max:1024'
        ]);

        $photoPath = null;
        $qrPath = null;

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo')->store('public/photo_pegawai');
            $photoPath = str_replace('public/', 'storage/', $photo);
        }

        if ($request->hasFile('qr_code')) {
            $qr = $request->file('qr_code')->store('public/qrcode_pegawai');
            $qrPath = str_replace('public/', 'storage/', $qr);
        }
        Employee::create([
            'id_employee' => $request->id_employee,
            'fullname' => $request->fullname,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'position_id' => $request->position_id, // Simpan position_id
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
            'qr_code' => $qrPath
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully');
    }

    // Menampilkan form edit employee dengan posisi yang sudah terpilih
    public function edit($id)
    {
        $roles = Role::all();
        $employee = Employee::findOrFail($id);
        $positions = Position::all(); // Ambil semua posisi dari database
        return view('employee.edit', compact('employee', 'positions','roles')); // Kirim data posisi ke view
    }

    // Mengupdate data employee
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $request->validate([
            'fullname' => 'required|string|max:100',
            'birth_place' => 'required|string|max:100',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email,' . $id . ',id_employee',
            'role_id' => 'nullable|integer',
            'position_id' => 'nullable|integer', // Validasi untuk position_id
            'password' => 'nullable|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qr_code' => 'nullable|image|mimes:png|max:1024'
        ]);

        $data = $request->only(['fullname', 'birth_place', 'birth_date', 'gender', 'phone_number', 'email', 'role_id', 'position_id']); // Tambahkan position_id

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
    // Hapus file lama jika ada
    if ($employee->photo) {
        Storage::delete(str_replace('storage/', 'public/', $employee->photo));
    }

    // Simpan file baru
    $path = $request->file('photo')->store('public/photo_pegawai');
    $data['photo'] = str_replace('public/', 'storage/', $path); // simpan path untuk URL publik
}

if ($request->hasFile('qr_code')) {
    // Hapus file lama jika ada
    if ($employee->qr_code) {
        Storage::delete(str_replace('storage/', 'public/', $employee->qr_code));
    }

    // Simpan file baru
    $path = $request->file('qr_code')->store('public/qrcode_pegawai');
    $data['qr_code'] = str_replace('public/', 'storage/', $path);
}

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    // Menghapus employee
   public function destroy($id)
{
    $employee = Employee::findOrFail($id);

    // Hapus file photo jika ada
    if ($employee->photo) {
        Storage::delete(str_replace('storage/', 'public/', $employee->photo));
    }

    // Hapus file qr_code jika ada
    if ($employee->qr_code) {
        Storage::delete(str_replace('storage/', 'public/', $employee->qr_code));
    }

    // Hapus record dari database
    $employee->delete();

    return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
}
}
