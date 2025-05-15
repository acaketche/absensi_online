<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Rapor;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
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

        // Menyimpan data rapor ke database
        Rapor::create([
            'id_student' => $request->id_student,
            'class_id' => $request->class_id,
            'academic_year_id' => $request->academic_year_id,
            'semester_id' => $request->semester_id,
            'report_date' => $request->report_date,
            'file_path' => $filePath ?? '',
            'description' => $request->description,
        ]);

        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        $classes = $query->get();
        $academicYears = AcademicYear::all();

        return view('rapor.rapor', compact('classes', 'academicYears'));
    }

    public function students($classId)
    {
        $class = Classes::with(['employee', 'academicYear'])->findOrFail($classId);
        $students = Student::where('class_id', $classId)
            ->with('rapor')
            ->get();

        return view('rapor.raporstudent', compact('class', 'students'));
    }

    public function create(Request $request)
    {
        $student = null;
        $class = null;

        if ($request->filled('id_student')) {
            $student = Student::findOrFail($request->id_student);
            $class = $student->class;
        }

        if ($request->filled('class_id')) {
            $class = Classes::findOrFail($request->class_id);
        }

        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('rapor.create', compact('student', 'class', 'academicYears', 'semesters'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id_student',
            'class_id' => 'required|exists:classes,class_id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'rapor_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingRapor = Rapor::where('id_student', $request->student_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->first();

        if ($existingRapor) {
            return redirect()->back()
                ->with('error', 'Rapor untuk siswa ini pada tahun ajaran dan semester yang dipilih sudah ada.')
                ->withInput();
        }

        $path = $request->file('rapor_file')->store('rapor', 'public');

        $rapor = new Rapor();
        $rapor->id_student = $request->student_id;
        $rapor->class_id = $request->class_id;
        $rapor->academic_year_id = $request->academic_year_id;
        $rapor->semester_id = $request->semester_id;
        $rapor->file_path = $path;
        $rapor->description = $request->description;
        $rapor->save();

        return redirect()->route('rapor.students', $request->class_id)
            ->with('success', 'Rapor berhasil diupload.');
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
            'semester_id' => 'required|exists:semesters,id',
            'rapor_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rapor = Rapor::findOrFail($id);

        $existingRapor = Rapor::where('id_student', $rapor->id_student)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRapor) {
            return redirect()->back()
                ->with('error', 'Rapor untuk siswa ini pada tahun ajaran dan semester yang dipilih sudah ada.')
                ->withInput();
        }

        if ($request->hasFile('rapor_file')) {
            if ($rapor->file_path) {
                Storage::disk('public')->delete($rapor->file_path);
            }

            $path = $request->file('rapor_file')->store('rapor', 'public');
            $rapor->file_path = $path;
        }

        $rapor->academic_year_id = $request->academic_year_id;
        $rapor->semester_id = $request->semester_id;
        $rapor->description = $request->description;
        $rapor->save();

        return redirect()->route('rapor.students', $rapor->class_id)
            ->with('success', 'Rapor berhasil diupdate.');
    }

    public function destroy($id)
    {
        $rapor = Rapor::findOrFail($id);
        $classId = $rapor->class_id;

        if ($rapor->file_path) {
            Storage::disk('public')->delete($rapor->file_path);
        }

        $rapor->delete();

        return redirect()->route('rapor.students', $classId)
            ->with('success', 'Rapor berhasil dihapus.');
    }
}
