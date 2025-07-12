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
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $employee = Auth::guard('employee')->user();
            $roleName = $employee->role->role_name ?? 'Tidak diketahui';

            Log::info('Akses Payment Controller', [
                'program' => 'Payment',
                'aktivitas' => 'Mengakses modul pembayaran',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
            ]);

            return $next($request);
        });
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

    // ... [rest of your existing methods with similar logging added] ...

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

    // ... [other methods with similar logging implementation] ...

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
}
