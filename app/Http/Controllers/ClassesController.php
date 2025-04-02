<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Employee;
use App\Models\AcademicYear;
use App\Models\Semester;

class ClassesController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        $classes = Classes::with('employee')->get();
        $waliKelas = Employee::where('role_id', '1')->get();
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        return view('classes.classes', compact('classes', 'waliKelas', 'activeAcademicYear', 'activeSemester'));
    }

    // Menampilkan form tambah kelas
    public function create()
    {
        $waliKelas = Employee::where('role_id', '1')->get();
        return view('classes.create', compact('waliKelas'));
    }

    // Menyimpan data kelas baru
    public function store(Request $request)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return redirect()->back()->withErrors(['error' => 'Tahun akademik atau semester aktif tidak ditemukan.'])->withInput();
        }

        $request->validate([
            'class_name' => 'required|string|max:50',
            'id_employee' => 'required|exists:employees,id_employee',
        ]);

        Classes::create([
            'class_id' => uniqid(), // Menyesuaikan jika class_id adalah string/UUID
            'class_name' => $request->class_name,
            'id_employee' => $request->id_employee,
            'academic_year_id' => $activeAcademicYear->id,
            'semester_id' => $activeSemester->id,
        ]);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    // Menampilkan detail kelas
    public function show($id)
    {
        $class = Classes::with('employee', 'students')->where('class_id', $id)->first();

        if (!$class) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json([
            'id' => $class->class_id,
            'class_name' => $class->class_name,
            'employee_name' => $class->employee->fullname ?? '-',
            'employee_nip' => $class->employee->id_employee ?? '-',
            'student_count' => $class->students->count(),
            'male_count' => $class->students->where('gender', 'L')->count(),
            'female_count' => $class->students->where('gender', 'P')->count(),
        ]);
    }

    // Menampilkan form edit kelas
    public function edit($id)
    {
        $class = Classes::where('class_id', $id)->firstOrFail();
        $waliKelas = Employee::where('role_id', '1')->get();
        return view('classes.edit', compact('class', 'waliKelas'));
    }

    // Memperbarui data kelas
    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:50',
            'id_employee' => 'nullable|exists:employees,id_employee',
        ]);

        $class = Classes::where('class_id', $id)->firstOrFail();

        $class->update([
            'class_name' => $request->class_name,
            'id_employee' => $request->id_employee ?? $class->id_employee,
        ]);

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    // Menghapus kelas
    public function destroy($id)
    {
        $class = Classes::where('class_id', $id)->firstOrFail();
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
