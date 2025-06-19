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
use PDF;

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

        $students = Student::where('academic_year_id', $academicYearId)
                            ->when($semesterId, function ($query) use ($semesterId) {
                                return $query->where('semester_id', $semesterId);
                            })
                            ->get();

        $academicYears = AcademicYear::all();
        $semesters = Semester::all();
        $classes = Classes::all();
        $statuses = AttendanceStatus::orderBy('created_at', 'desc')->get();
        $attendances = StudentAttendance::with(['student', 'class', 'status'])->get();
        return view('students.absensisiswa', compact('students', 'classes', 'activeAcademicYear', 'activeSemester',
            'academicYears', 'semesters', 'academicYearId', 'semesterId','attendances','statuses'));
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
    $request->validate([
        'id_student' => 'required|exists:students,id_student',
        'class_id' => 'required|exists:classes,id',
        'status_id' => 'required|in:1,2,3',
        'academic_year_id' => 'required|exists:academic_years,id',
        'semester_id' => 'required|exists:semesters,id',
        'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
    ]);

    $statusId = (int) $request->status_id;
    $now = now();

    // Cek apakah sudah absen hari ini
    $alreadyExists = StudentAttendance::where('id_student', $request->id_student)
        ->whereDate('attendance_date', $now->toDateString())
        ->exists();

    if ($alreadyExists) {
        return redirect()->back()->with('error', 'Siswa sudah melakukan absensi hari ini.');
    }

    $checkInTime = null;
    $isLate = false;

    if ($statusId === 1) {
        $checkInTime = $now->format('H:i:s');

        // Tandai terlambat jika masuk setelah 07:15
        $isLate = $checkInTime > '07:15:00';
    }

    $attendanceData = [
        'id_student' => $request->id_student,
        'class_id' => $request->class_id,
        'attendance_date' => $now->toDateString(),
        'attendance_time' => $now->format('H:i:s'),
        'check_in_time' => $checkInTime,
        'check_out_time' => null,
        'status_id' => $statusId,
        'academic_year_id' => $request->academic_year_id,
        'semester_id' => $request->semester_id,
        'document' => null,
    ];

    if ($request->hasFile('document')) {
        $attendanceData['document'] = $request->file('document')->store('attendance_proofs', 'public');
    }

    StudentAttendance::create($attendanceData);

    return redirect()->route('attendances.index')->with('success', $isLate ? 'Absensi berhasil, namun siswa terlambat.' : 'Absensi berhasil ditambahkan.');
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
public function updateCheckoutTime(Request $request, $attendanceId)
{
    $attendance = StudentAttendance::findOrFail($attendanceId);
    $now = now();

    $checkoutTime = $now->format('H:i:s');
    $attendanceDate = Carbon::parse($attendance->attendance_date);

    // Cek apakah check-out masih di hari yang sama dengan absensi
    $isSameDay = $now->isSameDay($attendanceDate);

    if (!$isSameDay) {
        // Hari berbeda, tidak boleh ubah status alpa
        return redirect()->back()->with('error', 'Check-out hanya bisa dilakukan di hari yang sama.');
    }

    // Jika status alpa dan masih hari yang sama, ubah jadi terlambat
    if ($attendance->status_id == 4) {
        $attendance->update([
            'check_out_time' => $checkoutTime,
            'status_id' => 5, // 5 = Terlambat
        ]);
        return redirect()->back()->with('success', 'Check-out berhasil. Status diubah menjadi terlambat.');
    }

    // Jika bukan alpa, hanya update check_out_time saja
    $attendance->update([
        'check_out_time' => $checkoutTime,
    ]);

    return redirect()->back()->with('success', 'Check-out berhasil.');
}
public function exportPdf(Request $request)
{
    $academicYearId = $request->academic_year_id;
    $semesterId = $request->semester_id;
    $statusId = $request->status_id;
    $startDate = $request->start_date;
    $endDate = $request->end_date;
    $classId = $request->class_id;

    $attendances = StudentAttendance::with(['student', 'class', 'status'])
        ->when($academicYearId, fn($q) => $q->where('academic_year_id', $academicYearId))
        ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
        ->when($classId, fn($q) => $q->where('class_id', $classId))
        ->when($statusId, fn($q) => $q->where('status_id', $statusId))
        ->when($startDate, fn($q) => $q->whereDate('attendance_date', '>=', $startDate))
        ->when($endDate, fn($q) => $q->whereDate('attendance_date', '<=', $endDate))
        ->orderBy('attendance_date', 'desc')
        ->get();

    // Ambil data filter untuk ditampilkan di header laporan
    $academicYear = $academicYearId ? AcademicYear::find($academicYearId)?->name : 'Semua';
    $semester = $semesterId ? Semester::find($semesterId)?->name : 'Semua';
    $class = $classId ? Classes::find($classId)?->name : 'Semua';
    $status = $statusId ? AttendanceStatus::find($statusId)?->name : 'Semua';

    $pdf = PDF::loadView('students.attendance_pdf', [
        'attendances' => $attendances,
        'academicYear' => $academicYear,
        'semester' => $semester,
        'class' => $class,
        'status' => $status,
        'startDate' => $startDate,
        'endDate' => $endDate,
    ])->setPaper('A4', 'landscape');

    return $pdf->download('laporan_absensi_siswa.pdf');
}

}
