<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\EmployeeAttendance;
use App\Models\StudentAttendance;
use App\Models\AttendanceStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Book;
use App\Models\BookLoan;

class DashboardController extends Controller
{
    public function Superadmin(Request $request)
    {
        // Total siswa
        $totalSiswa = Student::count();

        // Total pegawai
        $totalPegawai = Employee::count();

        // Tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Pegawai hadir hari ini
        $pegawaiHadir = Employee::whereHas('attendances', function ($query) use ($today) {
            $query->where('attendance_date', $today)
                  ->where('status_id', 1); // 1 = hadir
        })->count();

        // Siswa hadir hari ini
        $siswaHadir = Student::whereHas('attendances', function ($q) use ($today) {
            $q->where('attendance_date', $today)
              ->whereHas('status', function($query) {
                  $query->where('status_id', 1);
              });
        })->count();

        // Data untuk grafik presensi hari ini
        $todayEmployeeAttendances = EmployeeAttendance::with('status')
            ->where('attendance_date', $today)
            ->get();

        $todayStudentAttendances = StudentAttendance::with('status')
            ->where('attendance_date', $today)
            ->get();

        // Hitung per status untuk pegawai
        $employeeStatusCounts = $todayEmployeeAttendances->groupBy('status_id')
            ->map(function ($group) {
                return $group->count();
            });

        // Hitung per status untuk siswa
        $studentStatusCounts = $todayStudentAttendances->groupBy('status_id')
            ->map(function ($group) {
                return $group->count();
            });

        // Ambil semua status untuk label
        $statuses = AttendanceStatus::all();

        // Format data untuk chart
        $employeeChartData = [];
        $studentChartData = [];

        foreach ($statuses as $status) {
            $employeeChartData[$status->name] = $employeeStatusCounts[$status->id] ?? 0;
            $studentChartData[$status->name] = $studentStatusCounts[$status->id] ?? 0;
        }

        // Ambil bulan dan tahun dari request atau default ke sekarang
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Ambil libur
        $holidays = Holiday::with('academicYear')
            ->whereYear('holiday_date', $year)
            ->whereMonth('holiday_date', $month)
            ->get();

        $redDates = $holidays->filter(function ($holiday) {
            return $holiday->academic_year_id !== null;
        })->pluck('holiday_date')->toArray();

        // Ambil aktivitas login/logout terakhir dari log
        $logFile = storage_path('logs/laravel.log');
        $activities = [];

        if (File::exists($logFile)) {
            $lines = array_reverse(file($logFile)); // baca dari belakang
            foreach ($lines as $line) {
                if (str_contains($line, 'Aktivitas')) {
                    preg_match('/\{.*\}/', $line, $matches);
                    if ($matches) {
                        $json = json_decode($matches[0], true);
                        if ($json) {
                            $activities[] = $json;
                        }
                    }
                }
                if (count($activities) >= 10) break; // ambil maksimal 10 aktivitas terakhir
            }


        return view('auth.dashboard', compact(
            'redDates',
            'holidays',
            'month',
            'year',
            'totalSiswa',
            'totalPegawai',
            'pegawaiHadir',
            'siswaHadir',
            'activities',
            'employeeChartData',
            'studentChartData',
            'statuses'
        ));

    }
}
    public function TataUsaha(Request $request)
    {
         // Total siswa
        $totalSiswa = Student::count();

        // Total pegawai
        $totalPegawai = Employee::count();

        // Tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Pegawai hadir hari ini
        $pegawaiHadir = Employee::whereHas('attendances', function ($query) use ($today) {
            $query->where('attendance_date', $today)
                  ->where('status_id', 1); // 1 = hadir
        })->count();

        // Siswa hadir hari ini
        $siswaHadir = Student::whereHas('attendances', function ($q) use ($today) {
            $q->where('attendance_date', $today)
              ->whereHas('status', function($query) {
                  $query->where('status_id', 1);
              });
        })->count();

        // Data untuk grafik presensi hari ini
        $todayEmployeeAttendances = EmployeeAttendance::with('status')
            ->where('attendance_date', $today)
            ->get();

        $todayStudentAttendances = StudentAttendance::with('status')
            ->where('attendance_date', $today)
            ->get();

        // Hitung per status untuk pegawai
        $employeeStatusCounts = $todayEmployeeAttendances->groupBy('status_id')
            ->map(function ($group) {
                return $group->count();
            });

        // Hitung per status untuk siswa
        $studentStatusCounts = $todayStudentAttendances->groupBy('status_id')
            ->map(function ($group) {
                return $group->count();
            });

        // Ambil semua status untuk label
        $statuses = AttendanceStatus::all();

        // Format data untuk chart
        $employeeChartData = [];
        $studentChartData = [];

        foreach ($statuses as $status) {
            $employeeChartData[$status->name] = $employeeStatusCounts[$status->id] ?? 0;
            $studentChartData[$status->name] = $studentStatusCounts[$status->id] ?? 0;
        }

        // Ambil bulan dan tahun dari request atau default ke sekarang
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Ambil libur
        $holidays = Holiday::with('academicYear')
            ->whereYear('holiday_date', $year)
            ->whereMonth('holiday_date', $month)
            ->get();

        $redDates = $holidays->filter(function ($holiday) {
            return $holiday->academic_year_id !== null;
        })->pluck('holiday_date')->toArray();

        // Ambil aktivitas login/logout terakhir dari log
        $logFile = storage_path('logs/laravel.log');
        $activities = [];

        if (File::exists($logFile)) {
            $lines = array_reverse(file($logFile)); // baca dari belakang
            foreach ($lines as $line) {
                if (str_contains($line, 'Aktivitas')) {
                    preg_match('/\{.*\}/', $line, $matches);
                    if ($matches) {
                        $json = json_decode($matches[0], true);
                        if ($json) {
                            $activities[] = $json;
                        }
                    }
                }
                if (count($activities) >= 10) break; // ambil maksimal 10 aktivitas terakhir
            }


        return view('auth.dashboard', compact(
            'redDates',
            'holidays',
            'month',
            'year',
            'totalSiswa',
            'totalPegawai',
            'pegawaiHadir',
            'siswaHadir',
            'activities',
            'employeeChartData',
            'studentChartData',
            'statuses'
        ));

    }
    }


public function piket(Request $request)
{
    $today = Carbon::today()->toDateString();

    // Total siswa & pegawai
    $totalSiswa = Student::count();
    $totalPegawai = Employee::count();

    // Jumlah hadir hari ini
    $pegawaiHadir = Employee::whereHas('attendances', function ($query) use ($today) {
        $query->where('attendance_date', $today)
              ->where('status_id', 1); // 1 = Hadir
    })->count();

    $siswaHadir = Student::whereHas('attendances', function ($query) use ($today) {
        $query->where('attendance_date', $today)
              ->where('status_id', 1); // 1 = Hadir
    })->count();

    // Ambil data presensi hari ini
    $todayEmployeeAttendances = EmployeeAttendance::with('status')
        ->where('attendance_date', $today)
        ->get();

    $todayStudentAttendances = StudentAttendance::with('status')
        ->where('attendance_date', $today)
        ->get();

    // Hitung jumlah status
    $employeeStatusCounts = $todayEmployeeAttendances->groupBy('status_id')
        ->map->count();

    $studentStatusCounts = $todayStudentAttendances->groupBy('status_id')
        ->map->count();

    // Ambil semua status kehadiran
    $statuses = AttendanceStatus::all();

    // Format data untuk chart
    $employeeChartData = [];
    $studentChartData = [];

    foreach ($statuses as $status) {
        $employeeChartData[$status->name] = $employeeStatusCounts[$status->id] ?? 0;
        $studentChartData[$status->name] = $studentStatusCounts[$status->id] ?? 0;
    }

    // Ambil bulan & tahun dari request atau default ke sekarang
    $month = $request->input('month', Carbon::now()->month);
    $year = $request->input('year', Carbon::now()->year);

    // Ambil tanggal libur untuk bulan & tahun tertentu
    $holidays = Holiday::with('academicYear')
        ->whereYear('holiday_date', $year)
        ->whereMonth('holiday_date', $month)
        ->get();

    $redDates = $holidays->filter(function ($holiday) {
        return $holiday->academic_year_id !== null;
    })->pluck('holiday_date')->toArray();

    // Kirim data ke view
    return view('auth.dashboard_piket', compact(
        'totalSiswa',
        'totalPegawai',
        'pegawaiHadir',
        'siswaHadir',
        'employeeChartData',
        'studentChartData',
        'statuses',
        'redDates',
        'holidays',
         'month',
            'year'
    ));
}

   public function perpus()
{
    $today = Carbon::today()->toDateString();

    // Total siswa & pegawai
    $totalSiswa = Student::count();
    $totalPegawai = Employee::count();

    // Jumlah hadir hari ini
    $pegawaiHadir = Employee::whereHas('attendances', function ($query) use ($today) {
        $query->where('attendance_date', $today)
              ->where('status_id', 1); // 1 = Hadir
    })->count();

    $siswaHadir = Student::whereHas('attendances', function ($query) use ($today) {
        $query->where('attendance_date', $today)
              ->where('status_id', 1); // 1 = Hadir
    })->count();

    // Ambil data presensi hari ini
    $todayEmployeeAttendances = EmployeeAttendance::with('status')
        ->where('attendance_date', $today)
        ->get();

    $todayStudentAttendances = StudentAttendance::with('status')
        ->where('attendance_date', $today)
        ->get();

    // Hitung jumlah status
    $employeeStatusCounts = $todayEmployeeAttendances->groupBy('status_id')
        ->map->count();

    $studentStatusCounts = $todayStudentAttendances->groupBy('status_id')
        ->map->count();

    // Ambil semua status kehadiran
    $statuses = AttendanceStatus::all();

    // Format data untuk chart
    $employeeChartData = [];
    $studentChartData = [];

    foreach ($statuses as $status) {
        $employeeChartData[$status->name] = $employeeStatusCounts[$status->id] ?? 0;
        $studentChartData[$status->name] = $studentStatusCounts[$status->id] ?? 0;
    }

    // Ambil data buku dan peminjaman buku
    $books = Book::all();
    $bookLoans = BookLoan::with('book', 'student')->get();
$borrowedCount = $bookLoans->where('status', 'Dipinjam')->count();
$returnedCount = $bookLoans->where('status', 'Dikembalikan')->count();
    return view('auth.dashboard_perpus', compact(
        'totalSiswa',
        'totalPegawai',
        'pegawaiHadir',
        'siswaHadir',
        'employeeChartData',
        'studentChartData',
        'books',
        'bookLoans',
         'borrowedCount',
    'returnedCount'
    ));
}

public function walas(Request $request)
{
    $today = Carbon::today()->toDateString();

    // Total siswa & pegawai
    $totalSiswa = Student::count();
    $totalPegawai = Employee::count();

    // Jumlah hadir hari ini
    $pegawaiHadir = Employee::whereHas('attendances', function ($query) use ($today) {
        $query->where('attendance_date', $today)
              ->where('status_id', 1); // 1 = Hadir
    })->count();

    $siswaHadir = Student::whereHas('attendances', function ($query) use ($today) {
        $query->where('attendance_date', $today)
              ->where('status_id', 1); // 1 = Hadir
    })->count();

    // Ambil data presensi hari ini
    $todayEmployeeAttendances = EmployeeAttendance::with('status')
        ->where('attendance_date', $today)
        ->get();

    $todayStudentAttendances = StudentAttendance::with('status')
        ->where('attendance_date', $today)
        ->get();

    // Hitung jumlah status
    $employeeStatusCounts = $todayEmployeeAttendances->groupBy('status_id')
        ->map->count();

    $studentStatusCounts = $todayStudentAttendances->groupBy('status_id')
        ->map->count();

    // Ambil semua status kehadiran
    $statuses = AttendanceStatus::all();

    // Format data untuk chart
    $employeeChartData = [];
    $studentChartData = [];

    foreach ($statuses as $status) {
        $employeeChartData[$status->name] = $employeeStatusCounts[$status->id] ?? 0;
        $studentChartData[$status->name] = $studentStatusCounts[$status->id] ?? 0;
    }

    // Ambil bulan & tahun dari request atau default ke sekarang
    $month = $request->input('month', Carbon::now()->month);
    $year = $request->input('year', Carbon::now()->year);

    // Ambil tanggal libur untuk bulan & tahun tertentu
    $holidays = Holiday::with('academicYear')
        ->whereYear('holiday_date', $year)
        ->whereMonth('holiday_date', $month)
        ->get();

    $redDates = $holidays->filter(function ($holiday) {
        return $holiday->academic_year_id !== null;
    })->pluck('holiday_date')->toArray();

    // Kirim data ke view
    return view('auth.dashboard_piket', compact(
        'totalSiswa',
        'totalPegawai',
        'pegawaiHadir',
        'siswaHadir',
        'employeeChartData',
        'studentChartData',
        'statuses',
        'redDates',
        'holidays',
         'month',
            'year'
    ));
}

}

