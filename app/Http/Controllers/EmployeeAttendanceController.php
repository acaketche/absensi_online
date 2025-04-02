<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeAttendance;
use App\Models\Employee;
use App\Models\Status;
use Carbon\Carbon;

class EmployeeAttendanceController extends Controller
{
    // Menampilkan daftar absensi karyawan
    public function index()
    {
        $attendances = EmployeeAttendance::with('employee', 'status')->orderBy('attendance_date', 'desc')->get();
        return view('employee.absensipegawai', compact('attendances'));
    }

    // Menampilkan form untuk menambahkan absensi baru
    public function create()
    {
        $employees = Employee::all();
        $statuses = Status::all();
        return view('attendance.create', compact('employees', 'statuses'));
    }

    // Menyimpan data absensi baru
    public function store(Request $request)
    {
        $request->validate([
            'id_employee' => 'required|unique:employees,id_employee',
            'fullname' => 'required',
            'email' => 'nullable|email|unique:employees,email',
            'birth_place' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'phone_number' => 'required',
            'role_id' => 'required',
            'password' => 'required|min:6',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $employee = new Employee();
        $employee->id_employee = $request->id_employee;
        $employee->fullname = $request->fullname;
        $employee->email = $request->email;
        $employee->birth_place = $request->birth_place;
        $employee->birth_date = $request->birth_date;
        $employee->gender = $request->gender;
        $employee->phone_number = $request->phone_number;
        $employee->role_id = $request->role_id;
        $employee->password = bcrypt($request->password);

        // Simpan foto jika diunggah
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/employees', $filename);
            $employee->photo = $filename;
        }

        // Cek apakah QR Code perlu dibuat
        if ($request->generate_qr) {
            if (!$employee->qr_code) { // Hanya buat QR jika belum ada
                $qrCode = 'QR-' . $request->id_employee; // Bisa diganti dengan generator QR
                $employee->qr_code = $qrCode;
            }
        }

        $employee->save();

        return redirect()->back()->with('success', 'Pegawai berhasil ditambahkan!');
    }

    // Menampilkan detail absensi berdasarkan ID
    public function show($id)
    {
        $attendance = EmployeeAttendance::with('employee', 'status')->findOrFail($id);
        return view('attendance.show', compact('attendance'));
    }

    // Menampilkan form edit absensi
    public function edit($id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);
        $employees = Employee::all();
        $statuses = Status::all();
        return view('attendance.edit', compact('attendance', 'employees', 'statuses'));
    }

    // Memperbarui data absensi
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_employee' => 'required|exists:employees,id',
            'status_id' => 'required|exists:statuses,id',
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'latitude_in' => 'nullable|numeric',
            'longitude_in' => 'nullable|numeric',
            'latitude_out' => 'nullable|numeric',
            'longitude_out' => 'nullable|numeric',
        ]);

        $attendance = EmployeeAttendance::findOrFail($id);
        $attendance->update($request->all());

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    // Menghapus data absensi
    public function destroy($id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
