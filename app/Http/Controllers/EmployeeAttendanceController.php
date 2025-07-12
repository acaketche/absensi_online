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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

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
            'attendance_date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after:check_in',
            'status_id' => 'nullable|exists:attendance_status,status_id',
        ]);

        $statusId = $request->status_id;

        if (!$statusId) {
            // Cek apakah ada izin atau sakit
            if ($request->izin) {
                $statusId = 2; // Izin
            } elseif ($request->sakit) {
                $statusId = 3; // Sakit
            } elseif ($request->check_in) {
                $checkIn = \Carbon\Carbon::createFromFormat('H:i', $request->check_in);

                $startHadir = \Carbon\Carbon::createFromFormat('H:i', '06:30');
                $endHadir   = \Carbon\Carbon::createFromFormat('H:i', '07:30');
                $endTerlambat = \Carbon\Carbon::createFromFormat('H:i', '08:00');

                if ($checkIn >= $startHadir && $checkIn <= $endHadir) {
                    $statusId = 1; // Hadir (antara 06:30 dan 07:30)
                } elseif ($checkIn > $endHadir && $checkIn <= $endTerlambat) {
                    $statusId = 5; // Terlambat (antara 07:31 dan 08:00)
                } else {
                    $statusId = 4; // Alpha (di luar 06:30–08:00)
                }
            } else {
                $statusId = 4; // Alpha karena tidak absen
            }
        }

        $attendance = EmployeeAttendance::create([
            'id_employee' => $request->employee_id,
            'status_id' => $statusId,
            'attendance_date' => $request->attendance_date,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'academic_year_id' => $activeAcademicYear->id,
            'semester_id' => $activeSemester->id,
        ]);

        // Logging aktivitas
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        Log::info('Mencatat absensi pegawai', [
            'program' => 'Absensi Pegawai dan guru',
            'aktivitas' => 'Menambahkan data absensi pegawai dan guru',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'data' => [
                'id_pegawai' => $attendance->id_employee,
                'nama_pegawai' => $attendance->employee->fullname,
                'tanggal' => $attendance->attendance_date,
                'status' => $attendance->status->status_name ?? 'Tidak diketahui'
            ]
        ]);

        return redirect()->route('attendance.index')->with('success', 'Absensi berhasil ditambahkan.');

    } catch (\Exception $e) {
        \Log::error('Gagal mencatat absensi pegawai: ' . $e->getMessage());
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
public function update(Request $request, $id)
{
    $request->validate([
        'employee_id' => 'required|exists:employees,id_employee',
        'attendance_date' => 'required|date',
        'check_in' => 'nullable|date_format:H:i',
        'check_out' => [
            'nullable',
            'date_format:H:i',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->check_in && $value) {
                    try {
                        $checkIn = \Carbon\Carbon::createFromFormat('H:i', trim($request->check_in));
                        $checkOut = \Carbon\Carbon::createFromFormat('H:i', trim($value));
                        if ($checkOut <= $checkIn) {
                            $fail('Waktu keluar harus setelah waktu masuk.');
                        }
                    } catch (\Exception $e) {
                        $fail('Format waktu tidak valid.');
                    }
                }
            },
        ],
    ]);

    $attendance = EmployeeAttendance::findOrFail($id);
    $oldData = $attendance->toArray();

    // Logika status otomatis
    $statusId = 4; // Default Alpha
    if ($request->check_in) {
        try {
            $checkIn = \Carbon\Carbon::createFromFormat('H:i', trim($request->check_in));
            $startHadir   = \Carbon\Carbon::createFromTime(6, 30);
            $endHadir     = \Carbon\Carbon::createFromTime(7, 30);
            $endTerlambat = \Carbon\Carbon::createFromTime(8, 0);

            if ($checkIn >= $startHadir && $checkIn <= $endHadir) {
                $statusId = 1; // Hadir
            } elseif ($checkIn > $endHadir && $checkIn <= $endTerlambat) {
                $statusId = 5; // Terlambat
            } else {
                $statusId = 4; // Alpha
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Format jam masuk tidak valid.');
        }
    }

    $updateData = [
        'id_employee' => $request->employee_id,
        'status_id' => $statusId,
        'attendance_date' => $request->attendance_date,
        'check_in' => $request->check_in ? trim($request->check_in) : null,
    ];

    if ($request->has('check_out')) {
        $updateData['check_out'] = $request->check_out ? trim($request->check_out) : null;
    }

    $attendance->update($updateData);

    // Logging
    $employee = Auth::guard('employee')->user();
    $roleName = $employee->role->role_name ?? 'Tidak diketahui';

    \Log::info('Memperbarui absensi pegawai', [
        'program' => 'Absensi Pegawai dan guru',
        'aktivitas' => 'Memperbarui data absensi pegawai dan guru',
        'waktu' => now()->toDateTimeString(),
        'id_employee' => $employee->id_employee,
        'role' => $roleName,
        'ip' => $request->ip(),
        'data_lama' => $oldData,
        'data_baru' => $attendance->fresh()->toArray()
    ]);

    return redirect()->route('attendance.index')->with('success', 'Absensi berhasil diperbarui.');
}

 public function destroy(Request $request, $id)
    {
        $attendance = EmployeeAttendance::findOrFail($id);
        $attendanceData = $attendance->toArray();
        $attendance->delete();

        // Logging aktivitas
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        Log::warning('Menghapus absensi pegawai', [
            'program' => 'Absensi Pegawai dan guru',
            'aktivitas' => 'Menghapus data absensi pegawai dan guru',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => $employee->id_employee,
            'role' => $roleName,
            'ip' => $request->ip(),
            'data_terhapus' => $attendanceData
        ]);

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

