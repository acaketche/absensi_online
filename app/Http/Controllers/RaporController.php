<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rapor;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Storage;

class RaporController extends Controller
{
    /**
     * Menampilkan daftar rapor
     */
    public function index()
    {
        $rapor = Rapor::with(['student', 'class', 'academicYear', 'semester'])->get();
        return view('rapor.rapor', compact('rapor'));
    }

    /**
     * Menampilkan form tambah rapor
     */
    public function create()
    {
        $students = Student::all();
        $classes = Classes::all();
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('rapor.create', compact('students', 'classes', 'academicYears', 'semesters'));
    }

    /**
     * Menyimpan data rapor baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_student' => 'required',
            'class_id' => 'required',
            'academic_year_id' => 'required',
            'semester_id' => 'required',
            'report_date' => 'required|date',
            'file' => 'required|mimes:pdf|max:2048', // File PDF max 2MB
            'description' => 'nullable|string'
        ]);

        // Simpan file
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('rapor', 'public');
        }

        Rapor::create([
            'id_student' => $request->id_student,
            'class_id' => $request->class_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id' => $request->semester_id,
            'report_date' => $request->report_date,
            'file_path' => $filePath ?? '',
            'description' => $request->description,
        ]);

        return redirect()->route('rapor.index')->with('success', 'Rapor berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit rapor
     */
    public function edit($id)
    {
        $rapor = Rapor::findOrFail($id);
        $students = Student::all();
        $classes = Classes::all();
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('rapor.edit', compact('rapor', 'students', 'classes', 'academicYears', 'semesters'));
    }

    /**
     * Menyimpan perubahan data rapor
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_student' => 'required',
            'class_id' => 'required',
            'academic_year_id' => 'required',
            'semester_id' => 'required',
            'report_date' => 'required|date',
            'file' => 'nullable|mimes:pdf|max:2048',
            'description' => 'nullable|string'
        ]);

        $rapor = Rapor::findOrFail($id);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($rapor->file_path); // Hapus file lama
            $filePath = $request->file('file')->store('rapor', 'public');
            $rapor->file_path = $filePath;
        }

        $rapor->update([
            'id_student' => $request->id_student,
            'class_id' => $request->class_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id' => $request->semester_id,
            'report_date' => $request->report_date,
            'description' => $request->description,
        ]);

        return redirect()->route('rapor.index')->with('success', 'Rapor berhasil diperbarui.');
    }

    /**
     * Menghapus data rapor
     */
    public function destroy($id)
    {
        $rapor = Rapor::findOrFail($id);
        Storage::disk('public')->delete($rapor->file_path); // Hapus file PDF
        $rapor->delete();

        return redirect()->route('rapor.index')->with('success', 'Rapor berhasil dihapus.');
    }
}
