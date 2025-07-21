<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EmployeesController extends Controller
{
    // Menampilkan daftar employee
public function index()
{
    $roles = Role::all();
    $positions = Position::all(); // kalau ada posisi juga
    $employees = Employee::orderBy('fullname', 'asc')->get(); // urut abjad A-Z

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
            'role_id' => 'nullable|exists:roles,id',
            'position_id' => 'nullable|integer',
            'password' => 'nullable|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qr_code' => 'nullable|image|mimes:png|max:1024'
        ]);

        $photoPath = null;
        $qrPath = null;

        if ($request->hasFile('photo')) {
            $originalPhotoName = $request->file('photo')->getClientOriginalName();
            $photoPath = $request->file('photo')->storeAs('photo_pegawai', $originalPhotoName, 'public');
        }

        if ($request->hasFile('qr_code')) {
            $originalQRName = $request->file('qr_code')->getClientOriginalName();
            $qrPath = $request->file('qr_code')->storeAs('qrcode_pegawai', $originalQRName, 'public');
        }

        $employee = Employee::create([
            'id_employee' => $request->id_employee,
            'fullname' => $request->fullname,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'position_id' => $request->position_id,
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
            'qr_code' => $qrPath
        ]);

        $user = Auth::guard('employee')->user();
        $roleName = $user->role->role_name ?? 'Tidak diketahui';

        Log::info('Menambah pegawai baru', [
            'program' => 'Pegawai',
            'aktivitas' => 'Menambah data pegawai',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $user->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'data' => [
                'id_employee' => $employee->id_employee,
                'nama' => $employee->fullname,
                'email' => $employee->email
            ]
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

   public function update(Request $request, $id)
{
    $employee = Employee::findOrFail($id);

    $request->validate([
        'id_employee' => 'required|string|max:20|unique:employees,id_employee,' . $id . ',id_employee',
        'fullname' => 'required|string|max:100',
        'birth_place' => 'required|string|max:100',
        'birth_date' => 'required|date',
        'gender' => 'required|in:L,P',
        'phone_number' => 'required|string|max:20',
        'email' => 'required|email|unique:employees,email,' . $id . ',id_employee',
        'role_id' => 'nullable|integer',
        'position_id' => 'nullable|integer',
        'password' => 'nullable|min:6',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'qr_code' => 'nullable|image|mimes:png|max:1024'
    ]);

    $oldData = $employee->toArray();

    $data = $request->only(['id_employee','fullname', 'birth_place', 'birth_date', 'gender', 'phone_number', 'email', 'role_id', 'position_id']);

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    // Handle photo deletion or update
    if ($request->has('delete_photo') && $request->delete_photo) {
        // Delete existing photo
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }
        $data['photo'] = null;
    } elseif ($request->hasFile('photo')) {
        // Upload new photo
        if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
            Storage::disk('public')->delete($employee->photo);
        }
        $photoName = $request->file('photo')->getClientOriginalName();
        $data['photo'] = $request->file('photo')->storeAs('photo_pegawai', $photoName, 'public');
    }

    // Handle QR code deletion or update
    if ($request->has('delete_qr_code') && $request->delete_qr_code) {
        // Delete existing QR code
        if ($employee->qr_code && Storage::disk('public')->exists($employee->qr_code)) {
            Storage::disk('public')->delete($employee->qr_code);
        }
        $data['qr_code'] = null;
    } elseif ($request->hasFile('qr_code')) {
        // Upload new QR code
        if ($employee->qr_code && Storage::disk('public')->exists($employee->qr_code)) {
            Storage::disk('public')->delete($employee->qr_code);
        }
        $qrName = $request->file('qr_code')->getClientOriginalName();
        $data['qr_code'] = $request->file('qr_code')->storeAs('qrcode_pegawai', $qrName, 'public');
    }

    $employee->update($data);

    $user = Auth::guard('employee')->user();
    $roleName = $user->role->role_name ?? 'Tidak diketahui';

    Log::info('Mengedit data pegawai', [
        'program' => 'Pegawai',
        'aktivitas' => 'Mengupdate data pegawai',
        'waktu' => now()->toDateTimeString(),
        'id_employee' => $user->id_employee,
        'role' => $roleName,
        'ip' => $request->ip(),
        'data_lama' => $oldData,
        'data_baru' => $employee->fresh()->toArray()
    ]);

    return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui');
}

    // Menghapus employee
    public function destroy(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employeeData = $employee->toArray();

        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }

        if ($employee->qr_code) {
            Storage::disk('public')->delete($employee->qr_code);
        }

        $employee->delete();

        $user = Auth::guard('employee')->user();
        $roleName = $user->role->role_name ?? 'Tidak diketahui';

        Log::info('Menghapus data pegawai', [
            'program' => 'Pegawai',
            'aktivitas' => 'Menghapus data pegawai',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $user->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'data_terhapus' => $employeeData
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
  public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'fullname' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:employees,email,' . $user->id_employee . ',id_employee',
            'phone_number' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:L,P',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $oldData = $user->toArray();

        $user->fullname = $request->input('fullname');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->birth_place = $request->input('birth_place');
        $user->birth_date = $request->input('birth_date');
        $user->gender = $request->input('gender');

        if ($request->hasFile('photo')) {
            try {
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $user->photo = $request->file('photo')->store('photo_pegawai', 'public');
            } catch (\Exception $e) {
                return back()->withErrors(['photo' => 'Gagal mengunggah foto.'])->withInput();
            }
        }

        $user->save();

        $roleName = $user->role->role_name ?? 'Tidak diketahui';

        Log::info('Memperbarui profil', [
            'program' => 'Profil',
            'aktivitas' => 'Memperbarui data profil',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $user->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'perubahan' => array_diff_assoc($user->fresh()->toArray(), $oldData)
        ]);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        $roleName = $user->role->role_name ?? 'Tidak diketahui';

        Log::info('Memperbarui password', [
            'program' => 'Profil',
            'aktivitas' => 'Memperbarui password',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $user->id_employee,
            'role' => $roleName,
            'ip' => $request->ip()
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }
}
