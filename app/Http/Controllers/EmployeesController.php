<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmployeesController extends Controller
{
    // Menampilkan daftar employee
    public function index()
    {
        $employees = Employee::all();
        return view('employee.index', compact('employees'));
    }

    // Menampilkan form tambah employee
    public function create()
    {
        return view('employees.create');
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
            'role_id' => 'required|integer',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qr_code' => 'nullable|image|mimes:png|max:1024'
        ]);

        $photoPath = $request->file('photo') ? $request->file('photo')->store('photos') : null;
        $qrPath = $request->file('qr_code') ? $request->file('qr_code')->store('qrcodes') : null;

        Employee::create([
            'id_employee' => $request->id_employee,
            'fullname' => $request->fullname,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'photo' => $photoPath,
            'qr_code' => $qrPath
        ]);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully');
    }

    // Menampilkan form edit employee
    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        return view('employees.edit', compact('employee'));
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
            'role_id' => 'required|integer',
            'password' => 'nullable|min:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'qr_code' => 'nullable|image|mimes:png|max:1024'
        ]);

        $data = $request->only(['fullname', 'birth_place', 'birth_date', 'gender', 'phone_number', 'email', 'role_id']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos');
        }

        if ($request->hasFile('qr_code')) {
            if ($employee->qr_code) {
                Storage::delete($employee->qr_code);
            }
            $data['qr_code'] = $request->file('qr_code')->store('qrcodes');
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    // Menghapus employee
    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);

        if ($employee->photo) {
            Storage::delete($employee->photo);
        }
        if ($employee->qr_code) {
            Storage::delete($employee->qr_code);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
}
