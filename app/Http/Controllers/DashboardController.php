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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
 public function Superadmin(Request $request)
    {
        // Total siswa & pegawai
        $totalSiswa = Student::count();
        $totalPegawai = Employee::count();

        // Tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Pegawai hadir hari ini
        $pegawaiHadir = Employee::whereHas('attendances', function ($query) use ($today) {
            $query->where('attendance_date', $today)->where('status_id', 1);
        })->count();

        // Siswa hadir hari ini
        $siswaHadir = Student::whereHas('attendances', function ($q) use ($today) {
            $q->where('attendance_date', $today)->whereHas('status', function($query) {
                $query->where('status_id', 1);
            });
        })->count();

        // Data presensi pegawai & siswa hari ini
        $todayEmployeeAttendances = EmployeeAttendance::with('status')
            ->where('attendance_date', $today)->get();

        $todayStudentAttendances = StudentAttendance::with('status')
            ->where('attendance_date', $today)->get();

        // Hitung berdasarkan status
        $employeeStatusCounts = $todayEmployeeAttendances->groupBy('status_id')->map->count();
        $studentStatusCounts = $todayStudentAttendances->groupBy('status_id')->map->count();

        // Ambil semua status
        $statuses = AttendanceStatus::all();

        // Format untuk chart
        $employeeChartData = [];
        $studentChartData = [];
        foreach ($statuses as $status) {
            $employeeChartData[$status->status_name] = $employeeStatusCounts[$status->status_id] ?? 0;
            $studentChartData[$status->status_name] = $studentStatusCounts[$status->status_id] ?? 0;
        }

        // Ambil bulan & tahun dari request atau default sekarang
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // =====================
        // AMBIL DATA LIBUR
        // =====================
        $localHolidays = Holiday::with('academicYear')
            ->whereYear('holiday_date', $year)
            ->whereMonth('holiday_date', $month)
            ->get()
            ->map(function ($holiday) {
                return [
                    'source' => 'local',
                    'holiday_date' => $holiday->holiday_date,
                    'holiday_name' => $holiday->description,
                    'academic_year_name' => $holiday->academicYear->year_name ?? '-',
                ];
            });

        // Ambil data API libur nasional & cache 1 hari
        $apiHolidays = Cache::remember('external_holidays', 86400, function () {
            $response = Http::get('https://api-harilibur.netlify.app/api');
            return $response->successful() ? $response->json() : [];
        });

        // Filter API berdasarkan bulan & hindari duplikat
        $localDates = $localHolidays->pluck('holiday_date')->toArray();
        $filteredApi = collect($apiHolidays)->filter(function ($holiday) use ($month, $year, $localDates) {
            $date = Carbon::parse($holiday['holiday_date']);
            return $date->month == $month && $date->year == $year && !in_array($date->toDateString(), $localDates);
        })->map(function ($holiday) {
            return [
                'source' => 'api',
                'holiday_date' => $holiday['holiday_date'],
                'holiday_name' => $holiday['holiday_name'],
                'academic_year_name' => '-',
            ];
        });

        $combinedHolidays = $localHolidays->concat($filteredApi)->sortBy('holiday_date')->values();
        $redDates = $combinedHolidays->pluck('holiday_date')->toArray();

      $calendarEvents = $combinedHolidays->map(function ($holiday) {
    return [
        'title' => $holiday['holiday_name'],
        'start' => $holiday['holiday_date'],
        'allDay' => true,
        'color' => $holiday['source'] === 'local' ? '#FF6B6B' : '#FFA726',
        'textColor' => '#fff',
        'sourceType' => $holiday['source'],
    ];
})->toArray();

        // =====================
        // AMBIL AKTIVITAS LOG TERAKHIR
        // =====================
        $logFile = storage_path('logs/laravel.log');
        $activities = [];

        if (File::exists($logFile)) {
            $lines = array_reverse(file($logFile));
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
                if (count($activities) >= 10) break;
            }
        }

        return view('auth.dashboard', compact(
            'totalSiswa',
            'totalPegawai',
            'pegawaiHadir',
            'siswaHadir',
            'employeeChartData',
            'studentChartData',
            'statuses',
            'month',
            'year',
            'combinedHolidays',
            'redDates',
            'activities',
            'calendarEvents'
        ));
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

