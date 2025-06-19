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

class PaymentController extends Controller
{
  public function bayar(Request $request)
{
    $request->validate([
        'id_siswa' => 'required|exists:students,id_student',
        'amount' => 'required|numeric|min:1000',
        'month' => 'required|numeric|between:1,12'
    ]);

    try {
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return response()->json(['success' => false, 'message' => 'Tahun ajaran atau semester aktif tidak ditemukan.'], 400);
        }

        // Ambil data siswa beserta kelas
        $student = Student::with('class')->find($request->id_siswa);
        if (!$student || !$student->class) {
            return response()->json(['success' => false, 'message' => 'Data siswa atau kelas tidak ditemukan.'], 404);
        }

        // Ambil data SPP berdasarkan kelas, tahun ajaran, dan semester
        $spp = Spp::where('class_id', $student->class->class_id)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        if (!$spp) {
            return response()->json(['success' => false, 'message' => 'Data SPP tidak ditemukan untuk kelas dan tahun ajaran ini.'], 404);
        }

        // Cek apakah pembayaran bulan ini sudah dilakukan
        $existingPayment = Payment::where('id_student', $request->id_siswa)
            ->where('month', $request->month)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->exists();

        if ($existingPayment) {
            return response()->json(['success' => false, 'message' => 'Pembayaran untuk bulan ini sudah dilakukan.'], 400);
        }

        Payment::create([
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

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dicatat.']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Gagal mencatat pembayaran: ' . $e->getMessage()], 500);
    }
}

public function batalbayar(Request $request)
{
    $request->validate([
        'id_siswa' => 'required|exists:students,id_student',
        'month' => 'required|numeric|between:1,12'
    ]);

    try {
        $activeAcademicYear = AcademicYear::where('is_active', true)->first();
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return response()->json(['success' => false, 'message' => 'Tahun ajaran atau semester aktif tidak ditemukan.'], 400);
        }

        $payment = Payment::where('id_student', $request->id_siswa)
            ->where('month', $request->month)
            ->where('academic_year_id', $activeAcademicYear->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan.'], 404);
        }

        $payment->delete();

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil dibatalkan.']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Gagal membatalkan pembayaran: ' . $e->getMessage()], 500);
    }
}


    public function create(Request $request)
    {
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

                return redirect()
                    ->route('payment.listdata', $spp->id)
                    ->with('success', 'SPP berhasil dibuat');

            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->with('error', 'Gagal membuat SPP: '.$e->getMessage());
            }
        }

        $academicYears = AcademicYear::orderBy('year_name')->get();
        $semesters = Semester::orderBy('semester_name')->get();
        $classes = Classes::orderBy('class_name')->get();

        return view('spp.create', [
            'academicYears' => $academicYears,
            'semesters' => $semesters,
            'classes' => $classes,
            'activeAcademicYear' => $activeAcademicYear,
            'activeSemester' => $activeSemester
        ]);
    }
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

    // Ambil data SPP berdasarkan tahun akademik dan semester aktif
    $query = Spp::with(['academicYear', 'semester', 'classes'])
        ->where('academic_year_id', $activeAcademicYear->id)
        ->where('semester_id', $activeSemester->id);

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

    $spp = Spp::with(['academicYear', 'semester', 'classes'])
        ->findOrFail($id);

    $students = Student::where('class_id', $spp->class_id)
        ->orderBy('fullname')
        ->get();

    // Get payments for the specific month
    $payments = Payment::where('id_spp', $id)
        ->where('month', $month)
        ->get()
        ->keyBy('id_student');

    // Calculate summary for the selected month
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
        'activeAcademicYear' => $activeAcademicYear,
        'activeSemester' => $activeSemester,
        'semesterRange' => [
            'start' => $startDate->format('d F Y'),
            'end' => $endDate->format('d F Y')
        ]
    ]);
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ]);

        try {
            $spp = Spp::findOrFail($id);
            $spp->update([
                'amount' => $request->amount,
                'updated_at' => now()
            ]);

            return back()->with('success', 'Nominal SPP berhasil diperbarui');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui SPP: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Delete related payments first
            Payment::where('id_spp', $id)->delete();

            // Then delete the SPP record
            Spp::where('id', $id)->delete();

            DB::commit();

            return redirect()
                ->route('payment.listdata')
                ->with('success', 'Data SPP berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('payment.listdata')
                ->with('error', 'Gagal menghapus data SPP: '.$e->getMessage());
        }
    }
  public function downloadTemplate($sppId)
{
    $spp = Spp::with('classes')->findOrFail($sppId);

    // Generate filename with SPP ID and class name
    $filename = "Template_Pembayaran_SPP_Kelas_{$spp->classes->class_name}.xlsx";

    // Clean filename by removing special characters and replacing spaces
    $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '', str_replace(' ', '_', $filename));

    return Excel::download(
        new PaymentTemplateExport($sppId),
        $filename
    );
}
public function import(Request $request, $sppId)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls'
    ]);

    try {
        $spp = Spp::findOrFail($sppId);

        Excel::import(new PaymentsImport($sppId), $request->file('file'));

        return back()
            ->with('success', 'Data pembayaran berhasil diimport!')
            ->with('imported_spp', $spp->id);

    } catch (\Exception $e) {
        return back()
            ->with('error', 'Gagal mengimport data: '.$e->getMessage())
            ->with('import_errors', true);
    }
}
  public function exportAll()
    {
        return Excel::download(new AllPaymentsExport(), 'template_pembayaran_semua_kelas.xlsx');
    }

    public function exportByGrade($grade)
    {
        return Excel::download(new AllPaymentsExport($grade), 'template_pembayaran_kelas_' . $grade . '.xlsx');
    }

}
