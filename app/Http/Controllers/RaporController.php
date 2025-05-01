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
    public function index(Request $request)
    {
        // Mendapatkan tahun akademik dan semester aktif
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        // Mendapatkan parameter dari request (filter)
        $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
        $semesterId = $request->get('semester_id', $activeSemester->id ?? null);
        $classId = $request->get('class_id', null); // Tangkap filter kelas

        // Query untuk mendapatkan data siswa berdasarkan filter
        $students = Student::where('academic_year_id', $academicYearId)
            ->when($semesterId, function ($query) use ($semesterId) {
                return $query->where('semester_id', $semesterId);
            })
            ->when($classId, function ($query) use ($classId) {
                return $query->where('class_id', $classId);
            })
            ->get();

        // Mengambil data terkait lainnya
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();
        $classes = Classes::all();

        // Query untuk mengambil rapor berdasarkan filter
        $rapor = Rapor::where('academic_year_id', $academicYearId)
            ->where('semester_id', $semesterId)
            ->get();

        return view('rapor.rapor', compact(
            'students', 'classes', 'activeAcademicYear', 'activeSemester',
            'academicYears', 'semesters', 'academicYearId', 'semesterId', 'classId', 'rapor'
        ));
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

        // Menyimpan data rapor ke database
        Rapor::create([
            'id_student' => $request->id_student,
            'class_id' => $request->class_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id' => $request->semester_id,
            'report_date' => $request->report_date,
            'file_path' => $filePath ?? '', // Jika tidak ada file, simpan path kosong
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

        // Jika ada file baru yang diunggah
        if ($request->hasFile('file')) {
            // Menghapus file lama
            if (Storage::disk('public')->exists($rapor->file_path)) {
                Storage::disk('public')->delete($rapor->file_path);
            }

            // Menyimpan file baru
            $filePath = $request->file('file')->store('rapor', 'public');
            $rapor->file_path = $filePath;
        }

        // Mengupdate data rapor
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

        // Menghapus file terkait jika ada
        if (Storage::disk('public')->exists($rapor->file_path)) {
            Storage::disk('public')->delete($rapor->file_path);
        }

        // Menghapus data rapor
        $rapor->delete();

        return redirect()->route('rapor.index')->with('success', 'Rapor berhasil dihapus.');
    }
}
