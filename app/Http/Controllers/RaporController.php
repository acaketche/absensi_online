<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Rapor;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RaporController extends Controller
{
    public function classes(Request $request)
    {
        $query = Classes::with(['employee', 'academicYear'])
            ->withCount('students');

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $classes = $query->get();
        $academicYears = AcademicYear::all();

        return view('rapor.rapor', compact('classes', 'academicYears'));
    }

    public function students($classId)
    {
        $class = Classes::with(['employee', 'academicYear'])->findOrFail($classId);

        // Load students with their rapor data
        $students = Student::where('class_id', $classId)
            ->with(['rapor', 'academicYear', 'semester'])
            ->get();

        return view('rapor.raporstudent', compact('class', 'students'));
    }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'id_student'     => 'required|exists:students,id_student',
        'report_date'    => 'required|date',
        'report_file'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'description'    => 'nullable|string|max:500',
        'status_report'  => 'required|in:Belum Ada,Sudah Ada',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error_modal', true);
    }

    $student = Student::with(['class', 'academicYear', 'semester'])->findOrFail($request->id_student);

    $existingRapor = Rapor::where('id_student', $student->id_student)
        ->where('academic_year_id', $student->academic_year_id)
        ->where('semester_id', $student->semester_id)
        ->first();

    if ($existingRapor) {
        return redirect()->back()
            ->with('error', 'Rapor untuk siswa ini pada tahun ajaran dan semester yang sama sudah ada.')
            ->with('error_modal', true)
            ->with('student_id', $student->id_student);
    }

    // Upload file secara sederhana
    $filePath = null;
    if ($request->hasFile('report_file')) {
        $filePath = $request->file('report_file')->store('rapor', 'public');
    }

    Rapor::create([
        'id_student'        => $student->id_student,
        'class_id'          => $student->class_id,
        'academic_year_id'  => $student->academic_year_id,
        'semester_id'       => $student->semester_id,
        'report_date'       => $request->report_date,
        'description'       => $request->description,
        'file_path'         => $filePath, // simpan path yang benar langsung dari hasil store()
        'status_report'     => $request->status_report,
    ]);

    return redirect()->route('rapor.students', ['classId' => $student->class_id])
        ->with('success', 'Rapor berhasil diupload!');
}

    public function edit($id)
    {
        $rapor = Rapor::with(['student', 'academicYear', 'semester', 'class'])->findOrFail($id);
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('rapor.edit', compact('rapor', 'academicYears', 'semesters'));
    }

   public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'academic_year_id' => 'required|exists:academic_years,id',
        'semester_id'      => 'required|exists:semesters,id',
        'report_date'      => 'required|date',
        'description'      => 'nullable|string|max:500',
        'report_file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        'status_report'    => 'required|in:Belum Ada,Sudah Ada',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $rapor = Rapor::findOrFail($id);
    $student = Student::findOrFail($rapor->id_student);

    // Cek apakah ada rapor lain untuk tahun ajaran & semester yang sama
    $duplicate = Rapor::where('id_student', $rapor->id_student)
        ->where('academic_year_id', $request->academic_year_id)
        ->where('semester_id', $request->semester_id)
        ->where('id', '!=', $id)
        ->first();

    if ($duplicate) {
        return redirect()->back()
            ->with('error', 'Rapor untuk tahun ajaran dan semester ini sudah ada.')
            ->withInput();
    }

    // Update file jika ada file baru di-upload
    if ($request->hasFile('report_file')) {
        // Hapus file lama jika ada
        if ($rapor->file_path) {
            Storage::disk('public')->delete($rapor->file_path);
        }

        // Upload file baru langsung ke folder 'rapor' di storage/app/public
        $filePath = $request->file('report_file')->store('rapor', 'public');
        $rapor->file_path = $filePath;
    }

    // Update data lain
    $rapor->academic_year_id = $request->academic_year_id;
    $rapor->semester_id      = $request->semester_id;
    $rapor->report_date      = $request->report_date;
    $rapor->description      = $request->description;
    $rapor->status_report    = $request->status_report;
    $rapor->save();

    return redirect()->route('rapor.students', $rapor->class_id)
        ->with('success', 'Rapor berhasil diupdate.');
}
    public function destroy($id)
    {
        $rapor = Rapor::findOrFail($id);
        $classId = $rapor->class_id;

        // Hapus file rapor jika ada
        if ($rapor->file_path) {
            Storage::disk('public')->delete($rapor->file_path);
        }

        $rapor->delete();

        return redirect()->route('rapor.students', $classId)
            ->with('success', 'Rapor berhasil dihapus.');
    }


    public function show($id)
    {
        $rapor = Rapor::with(['student', 'academicYear', 'semester', 'class'])->findOrFail($id);
        return view('rapor.show', compact('rapor'));
    }

    public function download($id)
    {
        $rapor = Rapor::findOrFail($id);

        if (!$rapor->file_path || !Storage::disk('public')->exists($rapor->file_path)) {
            return redirect()->back()->with('error', 'File rapor tidak ditemukan.');
        }

        $student = Student::findOrFail($rapor->id_student);
        $fileName = 'Rapor_' . $student->fullname . '_' . $rapor->academicYear->name . '_' . $rapor->semester->name . '.' . pathinfo($rapor->file_path, PATHINFO_EXTENSION);

        return response()->download(storage_path('app/public/' . $rapor->file_path), $fileName);
    }
}
