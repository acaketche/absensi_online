<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentAttendance;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\AttendanceStatus;
use Illuminate\Support\Facades\Storage;

class StudentAttendanceController extends Controller
{
    /**
     * Menampilkan daftar absensi siswa.
     */
    public function index(Request $request)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
        $semesterId = $request->get('semester_id', $activeSemester->id ?? null);

       $search = $request->get('search');

$students = Student::where('academic_year_id', $academicYearId)
    ->when($semesterId, function ($query) use ($semesterId) {
        return $query->where('semester_id', $semesterId);
    })
    ->when($search, function ($query) use ($search) {
        return $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%");
        });
    })
    ->get();

// Ambil class_id jika hanya satu siswa ditemukan
$classIdFromStudent = null;
if ($students->count() === 1) {
    $classIdFromStudent = $students->first()->class_id ?? null;
}


        $academicYears = AcademicYear::all();
        $semesters = Semester::all();
        $classes = Classes::all();
        $statuses = AttendanceStatus::orderBy('created_at', 'desc')->get();
        $attendances = StudentAttendance::with(['student', 'class', 'subject', 'status'])->get();
      return view('students.absensisiswa', compact(
    'students', 'classes', 'activeAcademicYear', 'activeSemester',
    'academicYears', 'semesters', 'academicYearId', 'semesterId',
    'attendances', 'statuses', 'search', 'classIdFromStudent'
));
    }

    /**
     * Menampilkan form untuk menambahkan absensi.
     */
    public function create()
    {
        return view('attendances.create');
    }

    /**
     * Menyimpan data absensi siswa.
     */
    public function store(Request $request)
    {

        try {
        $request->validate([
            'id_student' => 'required|exists:students,id_student',
            'class_id' => 'nullable|exists:classes,id',
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i:s',
            'check_out_time' => 'nullable|date_format:H:i:s',
            'status_id' => 'required|exists:statuses,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id' => 'required|exists:semesters,id',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Menyimpan file bukti jika ada
        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('attendance_proofs', 'public');
        }

        StudentAttendance::create([
            'id_student' => $request->id_student,
            'class_id' => $request->class_id,
            'attendance_date' => $request->attendance_date,
            'check_in_time' => $request->check_in_time,
            'check_out_time' => $request->check_out_time,
            'status_id' => $request->status_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'academic_year_id' => $request->academic_year_id,
            'semester_id' => $request->semester_id,
            'document' => $documentPath
        ]);

      return redirect()
            ->route('student-attendance.index')
            ->with('success', 'Absensi berhasil ditambahkan.');

    } catch (\Exception $e) {
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
     * Menampilkan detail absensi.
     */
    public function show($id)
    {
        $attendance = StudentAttendance::with(['student', 'class', 'subject', 'status'])->findOrFail($id);
        return view('attendances.show', compact('attendance'));
    }

    /**
     * Menampilkan form edit absensi.
     */
    public function edit($id)
    {
        $attendance = StudentAttendance::findOrFail($id);
        return view('attendances.edit', compact('attendance'));
    }

    /**
     * Mengupdate data absensi.
     */
    public function update(Request $request, $id)
    {
        $attendance = StudentAttendance::findOrFail($id);

        $request->validate([
            'status_id' => 'required|exists:statuses,id',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // Menghapus file lama jika ada file baru
        if ($request->hasFile('document')) {
            if ($attendance->document) {
                Storage::disk('public')->delete($attendance->document);
            }
            $attendance->document = $request->file('document')->store('attendance_proofs', 'public');
        }

        $attendance->update($request->only('status_id', 'document'));

        return redirect()->route('attendances.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    /**
     * Menghapus absensi.
     */
    public function destroy($id)
    {
        $attendance = StudentAttendance::findOrFail($id);
        if ($attendance->document) {
            Storage::disk('public')->delete($attendance->document);
        }
        $attendance->delete();

        return redirect()->route('attendances.index')->with('success', 'Absensi berhasil dihapus.');
    }

// StudentAttendanceController.php
public function searchStudent(Request $request)
{
    $request->validate(['id_student' => 'required']);

    $student = Student::with('class')
        ->where('id_student', $request->id_student)
        ->first();

    if (!$student) {
        return response()->json([
            'success' => false,
            'message' => 'Siswa tidak ditemukan'
        ]);
    }

    return response()->json([
        'success' => true,
        'student' => [
            'id_student' => $student->id_student,
            'fullname' => $student->fullname,
            'class_id' => $student->class_id,
            'class_name' => $student->class->class_name ?? '-'
        ]
    ]);
}
}
