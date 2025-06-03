<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\AttendanceStatus;
use PDF;
use Carbon\Carbon;

class EmployeeAttendanceController extends Controller
{

public function index(Request $request)
{
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
    $semesterId = $request->get('semester_id', $activeSemester->id ?? null);

    $statuses = AttendanceStatus::all();
    $employees = Employee::all();

    $attendances = EmployeeAttendance::with(['employee', 'status'])
        ->when($academicYearId, function ($query) use ($academicYearId) {
            $query->where('academic_year_id', $academicYearId);
        })
        ->when($semesterId, function ($query) use ($semesterId) {
            $query->where('semester_id', $semesterId);
        })
        ->when($request->filled('status'), function ($query) use ($request) {
            $query->where('status_id', $request->status);
        })
        ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
            $query->whereBetween('attendance_date', [$request->start_date, $request->end_date]);
        })
        ->orderBy('attendance_date', 'desc')
        ->get();

    return view('employee.absensipegawai', compact(
        'attendances',
        'employees',
        'statuses',
        'academicYearId',
        'semesterId'
    ));
}

    // Menampilkan form untuk menambahkan absensi baru
    public function create()
    {
        $employees = Employee::all();
        $statuses = AttendanceStatus::all();
        return view('attendance.create', compact('employees', 'statuses'));
    }

   public function store(Request $request)
{
    try {
            $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
            $activeSemester = Semester::where('is_active', 1)->first();

            if (!$activeAcademicYear || !$activeSemester) {
                return redirect()->back()->with('error', 'Tahun Ajaran atau Semester aktif tidak ditemukan.');
            }
    $request->validate([
        'employee_id' => 'required|exists:employees,id_employee',
        'status_id' => 'required|exists:attendance_status,status_id',
        'attendance_date' => 'required|date',
        'check_in' => 'nullable|date_format:H:i',
        'check_out' => 'nullable|date_format:H:i|after:check_in',
    ]);

    EmployeeAttendance::create([
        'id_employee' => $request->employee_id, // sesuaikan kolom tabel
        'status_id' => $request->status_id,
        'attendance_date' => $request->attendance_date,
        'check_in' => $request->check_in,
        'check_out' => $request->check_out,
        'academic_year_id' => $activeAcademicYear->id,
        'semester_id' => $activeSemester->id,
    ]);
return redirect()->route('attendance.index')->with('success', 'Absensi berhasil ditambahkan.');
} catch (\Exception $e) {
    return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
}
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
        $statuses = AttendanceStatus::all();
        return view('attendance.edit', compact('attendance', 'employees', 'statuses'));
    }

    // Memperbarui data absensi
   public function update(Request $request, $id)
{

    $request->validate([
    'employee_id' => 'required|exists:employees,id_employee',
    'status_id' => 'required|exists:attendance_status,status_id',
    'attendance_date' => 'required|date',
    'check_in' => 'nullable|date_format:H:i',
    'check_out' => 'nullable|date_format:H:i|after:check_in', // Pastikan ini dijalankan jika ada check_out
]);

$attendance = EmployeeAttendance::findOrFail($id);

$attendance->update([
    'id_employee' => $request->employee_id,
    'status_id' => $request->status_id,
    'attendance_date' => $request->attendance_date,
    'check_in' => $request->check_in,
    'check_out' => $request->check_out ?? $attendance->check_out,
]);

    return redirect()->route('attendance.index')->with('success', 'Absensi berhasil diperbarui.');
}

    // Menghapus data absensi
    public function destroy($id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil dihapus.');
    }

 public function storeFromQR(Request $request)
{
    $request->validate([
        'qr_code' => 'required|string',
    ]);

    $employee = Employee::where('qr_code', $request->qr_code)->first();

    if (!$employee) {
        return response()->json(['message' => 'QR Code tidak ditemukan'], 404);
    }

    $attendance = EmployeeAttendance::create([
        'id_employee' => $employee->id,
        'status_id' => 1,
        'attendance_date' => Carbon::today(),
        'check_in' => Carbon::now()->format('H:i'),
    ]);

    return response()->json(['message' => 'Absensi berhasil dicatat', 'data' => $attendance]);
}

public function exportPdf(Request $request)
{
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
    $semesterId = $request->get('semester_id', $activeSemester->id ?? null);

    $attendances = EmployeeAttendance::with(['employee', 'status'])
        ->when($academicYearId, fn($query) => $query->where('academic_year_id', $academicYearId))
        ->when($semesterId, fn($query) => $query->where('semester_id', $semesterId))
        ->when($request->filled('status'), fn($query) => $query->where('status_id', $request->status))
        ->when($request->filled('start_date') && $request->filled('end_date'), function ($query) use ($request) {
            $query->whereBetween('attendance_date', [$request->start_date, $request->end_date]);
        })
        ->orderBy('attendance_date', 'desc')
        ->get();

    $pdf = PDF::loadView('employee.absenpdf', [
        'attendances' => $attendances,
        'academicYearId' => $academicYearId,
        'semesterId' => $semesterId,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
    ]);

    return $pdf->download('laporan_absensi_pegawai.pdf');
}
}

