<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentAttendance;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\AttendanceStatus;
use App\Models\StudentSemester;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class StudentAttendanceController extends Controller
{
public function index(Request $request)
{
    $academicYearId = $request->get('academic_year_id');
    $semesterId     = $request->get('semester_id');
    $classId        = $request->get('class_id');
    $statusId       = $request->get('status_id');
    $startDate      = $request->get('start_date');
    $endDate        = $request->get('end_date');

    // Ambil Tahun Ajaran Aktif jika belum dipilih
    $activeAcademicYear = AcademicYear::where('is_active', true)->first();
    if (!$academicYearId && $activeAcademicYear) {
        $academicYearId = $activeAcademicYear->id;
    }

    // Ambil daftar kelas berdasarkan tahun ajaran aktif
    $classes = Classes::when($academicYearId, fn($q) => $q->where('academic_year_id', $academicYearId))->get();

    // Ambil student_semesters berdasarkan filter class dan tahun ajaran aktif
    $studentSemesters = StudentSemester::with(['student', 'class', 'semester'])
        ->when($classId, fn($q) => $q->where('class_id', $classId))
        ->when($academicYearId, fn($q) => $q->where('academic_year_id', $academicYearId))
        ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
        ->get();

    // Ambil data siswa dari studentSemester
    $students = $studentSemesters->pluck('student')->unique('id_student')->values();
    $studentIds = $studentSemesters->pluck('student_id')->unique()->toArray();

    // Ambil semua data absensi dengan filter
    $allAttendances = StudentAttendance::with(['student', 'class', 'status'])
        ->whereIn('id_student', $studentIds)
        ->when($academicYearId, fn($q) => $q->where('academic_year_id', $academicYearId))
        ->when($semesterId,     fn($q) => $q->where('semester_id', $semesterId))
        ->when($classId,        fn($q) => $q->where('class_id', $classId))
        ->when($statusId,       fn($q) => $q->where('status_id', $statusId))
        ->when($startDate,      fn($q) => $q->whereDate('attendance_date', '>=', $startDate))
        ->when($endDate,        fn($q) => $q->whereDate('attendance_date', '<=', $endDate))
        ->orderBy('attendance_date', 'desc')
        ->get();

    // Pisahkan absensi pagi (yang memiliki check_in_time)
    $morningAttendances = $allAttendances->filter(function($attendance) {
        return !empty($attendance->check_in_time);
    });

    // Pisahkan absensi sore (yang memiliki check_out_time)
    $afternoonAttendances = $allAttendances->filter(function($attendance) {
        return !empty($attendance->check_out_time);
    });

    return view('students.absensisiswa', [
        'students'            => $students,
        'classes'             => $classes,
        'academicYears'       => AcademicYear::all(),
        'semesters'           => Semester::all(),
        'statuses'            => AttendanceStatus::latest()->get(),
        'morningAttendances'  => $morningAttendances,
        'afternoonAttendances'=> $afternoonAttendances,
        'allAttendances'      => $allAttendances, // Jika masih diperlukan
        'academicYearId'      => $academicYearId,
        'semesterId'          => $semesterId,
        'classId'             => $classId,
        'statusId'            => $statusId,
        'startDate'           => $startDate,
        'endDate'             => $endDate,
        'activeAcademicYear'  => $activeAcademicYear,
    ]);
}
   public function store(Request $request)
    {
        $request->validate([
            'id_student' => 'required|exists:students,id_student',
            'attendance_date' => 'required|date',
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $studentSemester = StudentSemester::where('student_id', $request->id_student)->latest()->first();

        if (!$studentSemester) {
            return redirect()->back()->with('error', 'Data semester siswa tidak ditemukan.');
        }

        $alreadyExists = StudentAttendance::where('id_student', $request->id_student)
            ->whereDate('attendance_date', $request->attendance_date)
            ->exists();

        if ($alreadyExists) {
            return redirect()->back()->with('error', 'Siswa sudah melakukan absensi di tanggal tersebut.');
        }

        $now = now();
        $checkInTime = null;
        $statusId = 1; // Default HADIR
        $isLate = false;

        if ($request->hasFile('document')) {
            $statusId = 2; // IZIN
        } else {
            $checkInTime = $now->format('H:i:s');
            if ($checkInTime > '07:15:00') {
                $statusId = 5; // TERLAMBAT
                $isLate = true;
            }
        }

        $attendanceData = [
            'id_student' => $request->id_student,
            'class_id' => $studentSemester->class_id,
            'academic_year_id' => $studentSemester->academic_year_id,
            'semester_id' => $studentSemester->semester_id,
            'attendance_date' => $request->attendance_date,
            'attendance_time' => $now->format('H:i:s'),
            'check_in_time' => $checkInTime,
            'check_out_time' => null,
            'status_id' => $statusId,
            'document' => $request->hasFile('document') ? $request->file('document')->store('attendance_proofs', 'public') : null,
        ];

        $attendance = StudentAttendance::create($attendanceData);

        // Logging aktivitas
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        Log::info('Mencatat absensi siswa', [
            'program' => 'Absensi Siswa',
            'aktivitas' => 'Menambahkan data absensi siswa',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'data' => [
                'id_student' => $attendance->id_student,
                'nama_siswa' => $attendance->student->fullname,
                'tanggal' => $attendance->attendance_date,
                'status' => $attendance->status->name
            ]
        ]);

        $message = match ($statusId) {
            2 => 'Absensi berhasil sebagai Izin.',
            5 => 'Absensi berhasil, namun siswa terlambat.',
            default => 'Absensi berhasil ditambahkan.',
        };

        return redirect()->route('student-attendance.index')->with('success', $message);
    }
public function destroy(Request $request, $id)
{
    try {
        $attendance = StudentAttendance::findOrFail($id);

        // Simpan data sebelum dihapus untuk log
        $studentName = $attendance->student->fullname ?? '-';
        $statusName = $attendance->status->status_name ?? '-';
        $studentId = $attendance->id_student;
        $attendanceDate = $attendance->attendance_date;

        // Hapus dokumen jika ada
        if ($attendance->document && Storage::exists($attendance->document)) {
            Storage::delete($attendance->document);
        }

        $attendance->delete();

        // Ambil data user login untuk log
        $employee = Auth::user();
        $roleName = $employee->role->role_name ?? 'Unknown';

        // Log aktivitas penghapusan
        Log::info('Menghapus data absensi siswa', [
            'program' => 'Absensi Siswa',
            'aktivitas' => 'Menghapus data absensi siswa',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee ?? null,
            'role' => $roleName,
            'ip' => $request->ip(),
            'data' => [
                'id_student' => $studentId,
                'nama_siswa' => $studentName,
                'tanggal' => $attendanceDate,
                'status' => $statusName
            ]
        ]);

        // Return JSON response for API calls
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil dihapus'
            ]);
        }

        // Return redirect for web requests
        return redirect()->route('student-attendance.index')
                         ->with('success', 'Data absensi berhasil dihapus.');

    } catch (\Exception $e) {
        // Return JSON response for API calls
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }

        // Return redirect for web requests
        return redirect()->back()
                         ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
    }
}
    public function updateCheckoutTime(Request $request, $attendanceId)
    {
        $attendance = StudentAttendance::findOrFail($attendanceId);
        $now = now();
        $checkoutTime = $now->format('H:i:s');
        $attendanceDate = Carbon::parse($attendance->attendance_date);

        if (!$now->isSameDay($attendanceDate)) {
            $attendance->update([
                'check_out_time' => null,
                'status_id' => 4, // Alpa
            ]);

            // Logging aktivitas
            $employee = Auth::guard('employee')->user();
            $roleName = $employee->role->role_name ?? 'Tidak diketahui';

            Log::warning('Checkout absensi siswa gagal', [
                'program' => 'Absensi Siswa',
                'aktivitas' => 'Checkout absensi siswa di hari berbeda',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_attendance' => $attendanceId,
                    'status' => 'Alpa'
                ]
            ]);

            return redirect()->back()->with('error', 'Check-out tidak dilakukan di hari yang sama. Status diubah menjadi Alpa.');
        }

        if ($attendance->status_id == 2) {
            return redirect()->back()->with('info', 'Siswa berstatus Izin. Tidak perlu check-out.');
        }

        if ($attendance->status_id == 1 || $attendance->status_id == 5) {
            $attendance->update(['check_out_time' => $checkoutTime]);

            // Logging aktivitas
            $employee = Auth::guard('employee')->user();
            $roleName = $employee->role->role_name ?? 'Tidak diketahui';

            Log::info('Checkout absensi siswa', [
                'program' => 'Absensi Siswa',
                'aktivitas' => 'Checkout absensi siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_attendance' => $attendanceId,
                    'check_out_time' => $checkoutTime
                ]
            ]);

            return redirect()->back()->with('success', 'Check-out berhasil.');
        }

        return redirect()->back()->with('warning', 'Status siswa tidak memungkinkan untuk check-out.');
    }


    public function exportPdf(Request $request)
    {
        $attendances = StudentAttendance::with(['student', 'class', 'status'])
            ->when($request->academic_year_id, fn($q) => $q->where('academic_year_id', $request->academic_year_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->status_id, fn($q) => $q->where('status_id', $request->status_id))
            ->when($request->start_date, fn($q) => $q->whereDate('attendance_date', '>=', $request->start_date))
            ->when($request->end_date, fn($q) => $q->whereDate('attendance_date', '<=', $request->end_date))
            ->orderBy('attendance_date', 'desc')
            ->get();

        $pdf = PDF::loadView('students.attendance_pdf', [
            'attendances' => $attendances,
            'academicYear' => $request->academic_year_id ? AcademicYear::find($request->academic_year_id)?->name : 'Semua',
            'semester' => $request->semester_id ? Semester::find($request->semester_id)?->name : 'Semua',
            'class' => $request->class_id ? Classes::find($request->class_id)?->name : 'Semua',
            'status' => $request->status_id ? AttendanceStatus::find($request->status_id)?->name : 'Semua',
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ])->setPaper('A4', 'landscape');

        return $pdf->download('laporan_absensi_siswa.pdf');
    }

public function search(Request $request)
{
    try {
        $idStudent = $request->query('id_student');

        $student = Student::where('id_student', $idStudent)
            ->select('id_student', 'fullname')
            ->with(['studentSemester.class' => function($query) {
                $query->select('class_id', 'class_name');
            }])
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        // Pastikan relasi studentSemester tidak kosong
        $semesterData = $student->studentSemester->first();

        return response()->json([
            'success' => true,
          'student' => [
    'id_student' => $student->id_student,
    'fullname' => $student->fullname,
    'nipd' => $student->id_student, // pastikan ini ada
    'class_id' => $semesterData->class_id ?? null,
    'class_name' => optional($semesterData->class)->class_name,
]
        ]);

    } catch (\Exception $e) {
        \Log::error('Student search error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Server error'
        ], 500);
    }

}
}
