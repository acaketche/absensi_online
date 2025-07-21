<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\Spp;
use App\Exports\PaymentTemplateExport;
use App\Imports\PaymentsImport;
use App\Exports\AllPaymentsExport;
use App\Imports\AllPaymentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function listData(Request $request)
{
    // Ambil tahun akademik dan semester aktif dulu
    $activeAcademicYear = AcademicYear::where('is_active', true)->first();
    $activeSemester = Semester::where('is_active', true)->first();

    if (!$activeAcademicYear || !$activeSemester) {
        return redirect()->back()->with('error', 'Tidak ada tahun akademik atau semester aktif');
    }

    // Pastikan tanggal awal dan akhir semester valid
    try {
        $startDate = \Carbon\Carbon::parse($activeSemester->start_date);
        $endDate = \Carbon\Carbon::parse($activeSemester->end_date);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Format tanggal semester tidak valid');
    }

    // Ambil daftar bulan yang termasuk dalam range semester aktif
    $validMonths = [];
    $currentDate = $startDate->copy();

    while ($currentDate <= $endDate) {
        $monthNumber = (int) $currentDate->format('n');
        $year = $currentDate->format('Y');

        // Jika bulan belum ada di array, tambahkan
        if (!isset($validMonths[$monthNumber])) {
            $validMonths[$monthNumber] = [
                'number' => $monthNumber,
                'name' => $this->getMonthName($monthNumber),
                'year' => $year
            ];
        }

        $currentDate->addMonth();
    }

    // Ambil bulan dari query string, default ke bulan sekarang
    $month = (int) ($request->get('month') ?? now()->format('n'));

    // Jika bulan tidak ada di validMonths, set ke bulan pertama yang ada
    if (!array_key_exists($month, $validMonths)) {
        $month = array_key_first($validMonths);
    }
$query = Spp::with(['academicYear', 'semester', 'classes'])
    ->join('classes', 'spp.class_id', '=', 'classes.class_id') // ✅ kolom benar
    ->select('spp.*')
    ->where('spp.academic_year_id', $activeAcademicYear->id)
    ->where('spp.semester_id', $activeSemester->id)
    ->orderByRaw("
        FIELD(SUBSTRING_INDEX(classes.class_name, ' ', 1), 'X', 'XI', 'XII'),
        CAST(SUBSTRING_INDEX(classes.class_name, ' ', -1) AS UNSIGNED)
    ");


    // Tambahkan filter kelas jika ada
    $classId = $request->get('class_id');
    if ($classId) {
        $query->where('class_id', $classId);
    }

    $sppData = $query->get();

    // Hitung total nominal tagihan
    $totalAmount = $sppData->sum('amount');

    // Hitung jumlah pembayaran pada bulan yang dipilih
    $paidAmount = Payment::whereIn('id_spp', $sppData->pluck('id'))
        ->where('month', $month)
        ->where('academic_year_id', $activeAcademicYear->id)
        ->where('semester_id', $activeSemester->id)
        ->sum('amount');

    // Hitung jumlah siswa belum bayar dan persentase pembayaran
    $paymentPercentage = $totalAmount > 0 ? round(($paidAmount / $totalAmount) * 100, 2) : 0;

    // Ambil semua kelas untuk filter dropdown
    $classes = Classes::orderBy('class_name')->get();

    return view('spp.list', [
        'sppData' => $sppData,
        'spp' => $sppData->first(),
        'classes' => $classes,
        'totalAmount' => $totalAmount,
        'paidAmount' => $paidAmount,
        'paymentPercentage' => $paymentPercentage,
        'months' => $validMonths,
        'currentMonth' => $month,
        'activeAcademicYear' => $activeAcademicYear,
        'activeSemester' => $activeSemester,
        'semesterRange' => [
            'start' => $startDate->format('d F Y'),
            'end' => $endDate->format('d F Y')
        ]
    ]);
}

    public function bayar(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate([
            'id_siswa' => 'required|exists:students,id_student',
            'amount' => 'required|numeric|min:1000',
            'month' => 'required|numeric|between:1,12'
        ]);

        try {
            // Ambil tahun ajaran dan semester aktif
            $activeAcademicYear = AcademicYear::where('is_active', true)->first();
            $activeSemester = Semester::where('is_active', true)->first();

            if (!$activeAcademicYear || !$activeSemester) {
                Log::warning('Tahun ajaran atau semester aktif tidak ditemukan', [
                    'program' => 'Payment',
                    'aktivitas' => 'Pembayaran SPP',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                ]);
                return response()->json(['success' => false, 'message' => 'Tahun ajaran atau semester aktif tidak ditemukan.'], 400);
            }

            // Ambil class_id dari tabel student_semester
            $classId = \DB::table('student_semester')
                ->where('student_id', $request->id_siswa)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->value('class_id');

            if (!$classId) {
                Log::warning('Kelas siswa tidak ditemukan', [
                    'program' => 'Payment',
                    'aktivitas' => 'Pembayaran SPP',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'student_id' => $request->id_siswa
                ]);
                return response()->json(['success' => false, 'message' => 'Kelas siswa tidak ditemukan pada tahun ajaran dan semester aktif.'], 404);
            }

            // Ambil data SPP berdasarkan class_id
            $spp = Spp::where('class_id', $classId)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->first();

            if (!$spp) {
                Log::warning('Data SPP tidak ditemukan', [
                    'program' => 'Payment',
                    'aktivitas' => 'Pembayaran SPP',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'class_id' => $classId
                ]);
                return response()->json(['success' => false, 'message' => 'Data SPP tidak ditemukan untuk kelas ini.'], 404);
            }

            // Cek apakah sudah ada pembayaran untuk bulan tersebut
            $existingPayment = Payment::where('id_student', $request->id_siswa)
                ->where('month', $request->month)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->exists();

            if ($existingPayment) {
                Log::warning('Pembayaran sudah dilakukan', [
                    'program' => 'Payment',
                    'aktivitas' => 'Pembayaran SPP',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'student_id' => $request->id_siswa,
                    'month' => $request->month
                ]);
                return response()->json(['success' => false, 'message' => 'Pembayaran untuk bulan ini sudah dilakukan.'], 400);
            }

            // Simpan pembayaran baru
            $payment = Payment::create([
                'id_student' => $request->id_siswa,
                'academic_year_id' => $activeAcademicYear->id,
                'semester_id' => $activeSemester->id,
                'id_spp' => $spp->id,
                'amount' => $request->amount,
                'status' => 'Lunas',
                'last_update' => now(),
                'notes' => 'Pembayaran via sistem',
                'month' => $request->month
            ]);

            Log::info('Pembayaran berhasil', [
                'program' => 'Payment',
                'aktivitas' => 'Pembayaran SPP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'student_id' => $request->id_siswa,
                'amount' => $request->amount,
                'month' => $request->month,
                'payment_id' => $payment->id
            ]);

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dicatat.']);

        } catch (\Exception $e) {
            Log::error('Gagal mencatat pembayaran', [
                'program' => 'Payment',
                'aktivitas' => 'Pembayaran SPP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function batalbayar(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate([
            'id_siswa' => 'required|exists:students,id_student',
            'month' => 'required|numeric|between:1,12'
        ]);

        try {
            // Ambil tahun ajaran dan semester aktif
            $activeAcademicYear = AcademicYear::where('is_active', true)->first();
            $activeSemester = Semester::where('is_active', true)->first();

            if (!$activeAcademicYear || !$activeSemester) {
                Log::warning('Tahun ajaran atau semester aktif tidak ditemukan', [
                    'program' => 'Payment',
                    'aktivitas' => 'Pembatalan Pembayaran',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                ]);
                return response()->json(['success' => false, 'message' => 'Tahun ajaran atau semester aktif tidak ditemukan.'], 400);
            }

            // Ambil data pembayaran berdasarkan siswa dan bulan
            $payment = Payment::where('id_student', $request->id_siswa)
                ->where('month', $request->month)
                ->where('academic_year_id', $activeAcademicYear->id)
                ->where('semester_id', $activeSemester->id)
                ->first();

            if (!$payment) {
                Log::warning('Data pembayaran tidak ditemukan', [
                    'program' => 'Payment',
                    'aktivitas' => 'Pembatalan Pembayaran',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'student_id' => $request->id_siswa,
                    'month' => $request->month
                ]);
                return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan.'], 404);
            }

            $paymentId = $payment->id;
            $payment->delete();

            Log::info('Pembayaran dibatalkan', [
                'program' => 'Payment',
                'aktivitas' => 'Pembatalan Pembayaran',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'student_id' => $request->id_siswa,
                'month' => $request->month,
                'payment_id' => $paymentId
            ]);

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dibatalkan.']);

        } catch (\Exception $e) {
            Log::error('Gagal membatalkan pembayaran', [
                'program' => 'Payment',
                'aktivitas' => 'Pembatalan Pembayaran',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal membatalkan pembayaran: ' . $e->getMessage()], 500);
        }
    }


    public function create(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        // Get active academic year and semester
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if ($request->has('simpan')) {
            $request->validate([
                'academic_year_id' => 'required',
                'semester_id' => 'required',
                'class_id' => 'required',
                'nominal' => 'required|numeric|min:1000'
            ]);

            try {
                $spp = Spp::create([
                    'id' => uniqid(),
                    'academic_year_id' => $request->academic_year_id,
                    'semester_id' => $request->semester_id,
                    'class_id' => $request->class_id,
                    'amount' => $request->nominal,
                    'created_at' => now()
                ]);

                Log::info('SPP berhasil dibuat', [
                    'program' => 'Payment',
                    'aktivitas' => 'Membuat SPP',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'spp_id' => $spp->id,
                    'class_id' => $request->class_id,
                    'amount' => $request->nominal
                ]);

                return redirect()
                    ->route('payment.listdata', $spp->id)
                    ->with('success', 'SPP berhasil dibuat');

            } catch (\Exception $e) {
                Log::error('Gagal membuat SPP', [
                    'program' => 'Payment',
                    'aktivitas' => 'Membuat SPP',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'error' => $e->getMessage()
                ]);
                return back()
                    ->withInput()
                    ->with('error', 'Gagal membuat SPP: '.$e->getMessage());
            }
        }

        $academicYears = AcademicYear::orderBy('year_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $classes = Classes::where('academic_year_id', $activeAcademicYear->id)
                          ->orderBy('class_name')
                          ->get();

        return view('spp.create', [
            'academicYears' => $academicYears,
            'semesters' => $semesters,
            'classes' => $classes,
            'activeAcademicYear' => $activeAcademicYear,
            'activeSemester' => $activeSemester
        ]);
    }


    public function destroy($id)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';
        $request = request();

        try {
            DB::beginTransaction();

            // Delete related payments first
            Payment::where('id_spp', $id)->delete();

            // Then delete the SPP record
            Spp::where('id', $id)->delete();

            DB::commit();

            Log::info('SPP berhasil dihapus', [
                'program' => 'Payment',
                'aktivitas' => 'Menghapus SPP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'spp_id' => $id
            ]);

            return redirect()
                ->route('payment.listdata')
                ->with('success', 'Data SPP berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus SPP', [
                'program' => 'Payment',
                'aktivitas' => 'Menghapus SPP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'spp_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('payment.listdata')
                ->with('error', 'Gagal menghapus data SPP: '.$e->getMessage());
        }
    }
    private function getMonthName($monthNumber)
{
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    return $months[$monthNumber] ?? '';
}
 public function kelola($id, Request $request)
{
    // Ambil data SPP lengkap
    $spp = Spp::with(['academicYear', 'semester', 'classes'])->findOrFail($id);

    // Gunakan tahun ajaran & semester dari SPP, bukan yang aktif
    $academicYear = $spp->academicYear;
    $semester = $spp->semester;

    if (!$academicYear || !$semester) {
        return redirect()->back()->with('error', 'Tahun akademik atau semester tidak ditemukan pada data SPP.');
    }

    // Validasi tanggal semester
    try {
        $startDate = \Carbon\Carbon::parse($semester->start_date);
        $endDate = \Carbon\Carbon::parse($semester->end_date);
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Format tanggal semester tidak valid');
    }

    // Dapatkan daftar bulan dari range semester
    $validMonths = [];
    $currentDate = $startDate->copy();
    while ($currentDate <= $endDate) {
        $monthNumber = (int) $currentDate->format('n');
        $year = $currentDate->format('Y');
        $validMonths[$monthNumber] = [
            'number' => $monthNumber,
            'name' => $this->getMonthName($monthNumber),
            'year' => $year
        ];
        $currentDate->addMonth();
    }

    // Ambil bulan dari query string
    $month = (int) ($request->get('month') ?? now()->format('n'));
    if (!array_key_exists($month, $validMonths)) {
        $month = array_key_first($validMonths);
    }

    // Ambil siswa dari student_semester sesuai tahun ajaran & semester dari SPP
    $students = Student::whereIn('id_student', function ($query) use ($spp) {
        $query->select('student_id')
            ->from('student_semester')
            ->where('class_id', $spp->class_id)
            ->where('academic_year_id', $spp->academic_year_id)
            ->where('semester_id', $spp->semester_id);
    })->orderBy('fullname')->get();

    // Ambil pembayaran berdasarkan SPP, bulan, tahun ajaran & semester dari SPP
    $payments = Payment::where('id_spp', $spp->id)
        ->where('month', $month)
        ->where('academic_year_id', $spp->academic_year_id)
        ->where('semester_id', $spp->semester_id)
        ->get()
        ->keyBy('id_student');

    // Hitung ringkasan
    $totalStudents = $students->count();
    $paidStudents = $payments->count();
    $unpaidStudents = $totalStudents - $paidStudents;
    $totalAmount = $payments->sum('amount');

    return view('spp.detailspp', [
        'spp' => $spp,
        'students' => $students,
        'payments' => $payments,
        'totalStudents' => $totalStudents,
        'paidStudents' => $paidStudents,
        'unpaidStudents' => $unpaidStudents,
        'totalAmount' => $totalAmount,
        'months' => $validMonths,
        'currentMonth' => $month,
        'activeAcademicYear' => $academicYear, // untuk ditampilkan
        'activeSemester' => $semester,
        'semesterRange' => [
            'start' => $startDate->format('d F Y'),
            'end' => $endDate->format('d F Y')
        ]
    ]);
}
}
