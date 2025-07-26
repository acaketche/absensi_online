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
use App\Models\PicketSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\BookCopy;
use App\Models\Classes;
use App\Models\Rapor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\StudentSemester;
use Illuminate\Support\Facades\DB;

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

// === HARI INI ===
$today = now()->toDateString();

$employeeToday = EmployeeAttendance::whereDate('attendance_date', $today)->get();
$studentToday = StudentAttendance::whereDate('attendance_date', $today)->get();

$employeeStatusCountsToday = $employeeToday->groupBy('status_id')->map->count();
$studentStatusCountsToday = $studentToday->groupBy('status_id')->map->count();

// === BULAN INI ===
$currentMonth = now()->format('m');
$currentYear = now()->format('Y');

$employeeMonth = EmployeeAttendance::whereMonth('attendance_date', $currentMonth)
    ->whereYear('attendance_date', $currentYear)->get();

$studentMonth = StudentAttendance::whereMonth('attendance_date', $currentMonth)
    ->whereYear('attendance_date', $currentYear)->get();

$employeeStatusCountsMonth = $employeeMonth->groupBy('status_id')->map->count();
$studentStatusCountsMonth = $studentMonth->groupBy('status_id')->map->count();

// === CHART DATA ===
$employeeChartToday = [];
$employeeChartMonth = [];
$studentChartToday = [];
$studentChartMonth = [];

foreach ($statuses as $status) {
    $statusName = strtolower($status->status_name);

    // === Pegawai - Hari Ini ===
    if (in_array($statusName, ['izin', 'sakit'])) {
        $employeeChartToday['Izin/Sakit'] = ($employeeChartToday['Izin/Sakit'] ?? 0) + ($employeeStatusCountsToday[$status->status_id] ?? 0);
    } else {
        $employeeChartToday[$status->status_name] = $employeeStatusCountsToday[$status->status_id] ?? 0;
    }

    // === Pegawai - Bulan Ini ===
    if (in_array($statusName, ['izin', 'sakit'])) {
        $employeeChartMonth['Izin/Sakit'] = ($employeeChartMonth['Izin/Sakit'] ?? 0) + ($employeeStatusCountsMonth[$status->status_id] ?? 0);
    } else {
        $employeeChartMonth[$status->status_name] = $employeeStatusCountsMonth[$status->status_id] ?? 0;
    }

    // === Siswa - Hari Ini ===
    if (in_array($statusName, ['izin', 'sakit'])) {
        $studentChartToday['Izin/Sakit'] = ($studentChartToday['Izin/Sakit'] ?? 0) + ($studentStatusCountsToday[$status->status_id] ?? 0);
    } else {
        $studentChartToday[$status->status_name] = $studentStatusCountsToday[$status->status_id] ?? 0;
    }

    // === Siswa - Bulan Ini ===
    if (in_array($statusName, ['izin', 'sakit'])) {
        $studentChartMonth['Izin/Sakit'] = ($studentChartMonth['Izin/Sakit'] ?? 0) + ($studentStatusCountsMonth[$status->status_id] ?? 0);
    } else {
        $studentChartMonth[$status->status_name] = $studentStatusCountsMonth[$status->status_id] ?? 0;
    }
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

      $logFile = storage_path('logs/laravel.log');
$activities = [];

if (File::exists($logFile)) {
    $lines = array_reverse(file($logFile)); // dari bawah ke atas
    foreach ($lines as $line) {
        if (str_contains($line, '{') && str_contains($line, 'program')) {
            preg_match('/\{.*\}/', $line, $matches);
            if ($matches) {
                $json = json_decode($matches[0], true);
                if ($json && isset($json['program']) && isset($json['aktivitas'])) {
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
             'employeeChartToday',
    'employeeChartMonth',
    'studentChartToday',
    'studentChartMonth',
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
    $today = Carbon::today();
    $currentMonth = $today->format('m');
    $currentYear = $today->format('Y');

    // 1. Rekap jumlah siswa per tingkat
    $siswaPerTingkat = DB::table('student_semester')
        ->join('classes', 'student_semester.class_id', '=', 'classes.class_id')
        ->select('classes.class_level', DB::raw('COUNT(*) as total'))
        ->groupBy('classes.class_level')
        ->orderBy('classes.class_level', 'asc')
        ->get();

    // 2. Status pembayaran siswa
    $totalSiswa = Student::count();
    $siswaLunas = Student::whereHas('payments', function($q) {
        $q->where('status', 'lunas');
    })->count();
    $siswaBelumLunas = $totalSiswa - $siswaLunas;

    // 3. Absensi pegawai dan siswa (harian & bulanan)
    $statuses = AttendanceStatus::all();

    // Hari ini
    $employeeToday = EmployeeAttendance::whereDate('attendance_date', $today)->get();
    $studentToday = StudentAttendance::whereDate('attendance_date', $today)->get();

    $employeeStatusCountsToday = $employeeToday->groupBy('status_id')->map->count();
    $studentStatusCountsToday = $studentToday->groupBy('status_id')->map->count();

    // Bulan ini
    $employeeMonth = EmployeeAttendance::whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)->get();

    $studentMonth = StudentAttendance::whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)->get();

    $employeeStatusCountsMonth = $employeeMonth->groupBy('status_id')->map->count();
    $studentStatusCountsMonth = $studentMonth->groupBy('status_id')->map->count();

    // CHART DATA
    $employeeChartToday = [];
    $employeeChartMonth = [];
    $studentChartToday = [];
    $studentChartMonth = [];
    $absensiChartData = [];

    foreach ($statuses as $status) {
        $statusName = strtolower($status->status_name);

        // Pegawai - Hari Ini
        if (in_array($statusName, ['izin', 'sakit'])) {
            $employeeChartToday['Izin/Sakit'] = ($employeeChartToday['Izin/Sakit'] ?? 0) + ($employeeStatusCountsToday[$status->status_id] ?? 0);
        } else {
            $employeeChartToday[$status->status_name] = $employeeStatusCountsToday[$status->status_id] ?? 0;
        }

        // Pegawai - Bulan Ini
        if (in_array($statusName, ['izin', 'sakit'])) {
            $employeeChartMonth['Izin/Sakit'] = ($employeeChartMonth['Izin/Sakit'] ?? 0) + ($employeeStatusCountsMonth[$status->status_id] ?? 0);
        } else {
            $employeeChartMonth[$status->status_name] = $employeeStatusCountsMonth[$status->status_id] ?? 0;
        }

        // Siswa - Hari Ini
        if (in_array($statusName, ['izin', 'sakit'])) {
            $studentChartToday['Izin/Sakit'] = ($studentChartToday['Izin/Sakit'] ?? 0) + ($studentStatusCountsToday[$status->status_id] ?? 0);
        } else {
            $studentChartToday[$status->status_name] = $studentStatusCountsToday[$status->status_id] ?? 0;
        }

        // Siswa - Bulan Ini
        if (in_array($statusName, ['izin', 'sakit'])) {
            $studentChartMonth['Izin/Sakit'] = ($studentChartMonth['Izin/Sakit'] ?? 0) + ($studentStatusCountsMonth[$status->status_id] ?? 0);
        } else {
            $studentChartMonth[$status->status_name] = $studentStatusCountsMonth[$status->status_id] ?? 0;
        }

        // Absensi Chart Harian untuk bar
        $absensiChartData[$status->status_name] = $employeeStatusCountsToday[$status->status_id] ?? 0;
    }

    // 4. Jumlah pegawai
    $totalPegawai = Employee::count();

    // 5. Tahun ajaran aktif
    $tahunAjaranAktif = AcademicYear::where('is_active', 1)->first();

    // 6. Kalender libur
    $month = $request->input('month', date('m'));
    $year = $request->input('year', date('Y'));

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

    $apiHolidays = Cache::remember('external_holidays', 86400, function () {
        $response = Http::get('https://api-harilibur.netlify.app/api');
        return $response->successful() ? $response->json() : [];
    });

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

    return view('auth.dashboard_tu', compact(
        'siswaPerTingkat',
        'totalPegawai',
        'siswaLunas',
        'siswaBelumLunas',
        'tahunAjaranAktif',
        'month',
        'year',
        'combinedHolidays',
        'redDates',
        'calendarEvents',
        'absensiChartData',
        'employeeChartToday',
        'employeeChartMonth',
        'studentChartToday',
        'studentChartMonth'
    ), [
        'totalSiswa' => $siswaPerTingkat->sum('total')
    ]);
}

public function piket(Request $request)
{
    $today = Carbon::today();
    $user = Auth::guard('employee')->user();

// Cek apakah user sedang bertugas piket hari ini
$todayPicketSchedule = null;

if ($user) {
    $todayPicketSchedule = PicketSchedule::with('employee')
        ->where('employee_id', $user->id_employee)
        ->whereDate('picket_date', $today)
        ->first();
}

    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    if (!$activeAcademicYear || !$activeSemester) {
        return back()->withErrors(['message' => 'Tahun ajaran atau semester aktif belum disetel.']);
    }

    // Total siswa aktif (berdasarkan semester aktif)
    $totalSiswa = Student::whereHas('studentSemester', function ($q) use ($activeAcademicYear, $activeSemester) {
        $q->where('academic_year_id', $activeAcademicYear->id)
          ->where('semester_id', $activeSemester->id);
    })->count();

    // === Ambil Semua Status ===
    $statuses = AttendanceStatus::all();

    // === Kehadiran Hari Ini ===
    $todayStudentAttendances = StudentAttendance::with('status')
        ->whereDate('attendance_date', $today)
        ->whereHas('student.studentSemester', function ($q) use ($activeAcademicYear, $activeSemester) {
            $q->where('academic_year_id', $activeAcademicYear->id)
              ->where('semester_id', $activeSemester->id);
        })
        ->get();

    $studentStatusCountsToday = $todayStudentAttendances->groupBy('status_id')->map->count();
    $studentChartToday = [];

    foreach ($statuses as $status) {
        $statusName = strtolower($status->status_name);

        if (in_array($statusName, ['izin', 'sakit'])) {
            $studentChartToday['Izin/Sakit'] = ($studentChartToday['Izin/Sakit'] ?? 0) + ($studentStatusCountsToday[$status->status_id] ?? 0);
        } else {
            $studentChartToday[$status->status_name] = $studentStatusCountsToday[$status->status_id] ?? 0;
        }
    }

    $siswaHadir = $studentChartToday['Hadir'] ?? 0;
    $siswaTidakHadir = ($studentChartToday['Izin/Sakit'] ?? 0) + ($studentChartToday['Alpa'] ?? 0);
    $siswaTerlambat = $studentChartToday['Terlambat'] ?? 0;

    // === Kehadiran Bulan Ini ===
    $currentMonth = now()->format('m');
    $currentYear = now()->format('Y');

    $monthStudentAttendances = StudentAttendance::with('status')
        ->whereMonth('attendance_date', $currentMonth)
        ->whereYear('attendance_date', $currentYear)
        ->whereHas('student.studentSemester', function ($q) use ($activeAcademicYear, $activeSemester) {
            $q->where('academic_year_id', $activeAcademicYear->id)
              ->where('semester_id', $activeSemester->id);
        })
        ->get();

    $studentStatusCountsMonth = $monthStudentAttendances->groupBy('status_id')->map->count();
    $studentChartMonth = [];

    foreach ($statuses as $status) {
        $statusName = strtolower($status->status_name);

        if (in_array($statusName, ['izin', 'sakit'])) {
            $studentChartMonth['Izin/Sakit'] = ($studentChartMonth['Izin/Sakit'] ?? 0) + ($studentStatusCountsMonth[$status->status_id] ?? 0);
        } else {
            $studentChartMonth[$status->status_name] = $studentStatusCountsMonth[$status->status_id] ?? 0;
        }
    }

    // === Data Kehadiran Per Kelas ===
    $classes = Classes::all();
    $classAttendanceData = [];

    foreach ($classes as $class) {
        $classAttendances = StudentAttendance::with('status')
            ->whereDate('attendance_date', $today)
            ->whereHas('student.studentSemester', function ($query) use ($class, $activeAcademicYear, $activeSemester) {
                $query->where('class_id', $class->class_id)
                      ->where('academic_year_id', $activeAcademicYear->id)
                      ->where('semester_id', $activeSemester->id);
            })
            ->get();

        $totalStudents = Student::whereHas('studentSemester', function ($q) use ($class, $activeAcademicYear, $activeSemester) {
            $q->where('class_id', $class->class_id)
              ->where('academic_year_id', $activeAcademicYear->id)
              ->where('semester_id', $activeSemester->id);
        })->count();

        $classStatusCounts = $classAttendances->groupBy('status_id')->map->count();
        $hadir = 0;
        $izinSakit = 0;
        $alpa = 0;
        $terlambat = 0;

        foreach ($statuses as $status) {
            $statusName = strtolower($status->status_name);
            $count = $classStatusCounts[$status->status_id] ?? 0;

            if ($statusName == 'hadir') $hadir += $count;
            elseif (in_array($statusName, ['izin', 'sakit'])) $izinSakit += $count;
            elseif ($statusName == 'alpa') $alpa += $count;
            elseif ($statusName == 'terlambat') $terlambat += $count;
        }

        $classAttendanceData[] = [
            'class_name' => $class->class_name,
            'present' => $hadir,
            'absent' => $izinSakit + $alpa,
            'late' => $terlambat,
            'total' => $totalStudents
        ];
    }
// === Kehadiran Minggu Ini ===
$weekStudentAttendances = StudentAttendance::with('status')
    ->whereBetween('attendance_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
    ->whereHas('student.studentSemester', function ($q) use ($activeAcademicYear, $activeSemester) {
        $q->where('academic_year_id', $activeAcademicYear->id)
          ->where('semester_id', $activeSemester->id);
    })
    ->get();

$studentStatusCountsWeek = $weekStudentAttendances->groupBy('status_id')->map->count();
$studentChartWeek = [];

foreach ($statuses as $status) {
    $statusName = strtolower($status->status_name);

    if (in_array($statusName, ['izin', 'sakit'])) {
        $studentChartWeek['Izin/Sakit'] = ($studentChartWeek['Izin/Sakit'] ?? 0) + ($studentStatusCountsWeek[$status->status_id] ?? 0);
    } else {
        $studentChartWeek[$status->status_name] = $studentStatusCountsWeek[$status->status_id] ?? 0;
    }
}
return view('auth.dashboard_piket', compact(
    'totalSiswa',
    'siswaHadir',
    'siswaTidakHadir',
    'siswaTerlambat',
    'studentChartToday',
    'studentChartWeek',
    'studentChartMonth',
    'classAttendanceData',
    'todayPicketSchedule' // <--- kirim ke view
));
}

   public function perpus()
{
     $today = Carbon::today()->toDateString();

        // Get total books
        $totalBooks = Book::count();

        // Get total copies (sum of all copies from all books)
        $totalCopies = BookCopy::count();

        // Get active loans (not returned)
        $activeLoans = BookLoan::where('status', 'Dipinjam')->count();

        // Get today's loans
        $todayLoans = BookLoan::whereDate('loan_date', $today)->count();

        // Get returned loans
        $returnedLoans = BookLoan::where('status', 'Dikembalikan')->count();

        // Get available copies (not currently loaned)
        $availableCopies = BookCopy::whereDoesntHave('loans', function($query) {
            $query->where('status', 'Dipinjam');
        })->count();

        // Get books grouped by class level
        $booksPerLevel = Book::join('classes', 'books.class_id', '=', 'classes.class_id')
    ->select('classes.class_level', DB::raw('count(*) as total'))
    ->groupBy('classes.class_level')
    ->orderBy('classes.class_level')
    ->get();

        // Get recent books (5 most recently added)
        $recentBooks = Book::withCount('copies')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function($book) {
                $book->stock = $book->copies_count - $book->loans()->where('status', 'Dipinjam')->count();
                return $book;
            });

        // Get recent loans (5 most recent)
        $recentLoans = BookLoan::with(['book', 'student'])
            ->orderBy('loan_date', 'desc')
            ->take(5)
            ->get();

        // Get active academic year and semester (assuming these relationships exist)
        $activeAcademicYear = null;
        $activeSemester = null;
        if (class_exists('\App\Models\AcademicYear')) {
            $activeAcademicYear = \App\Models\AcademicYear::where('is_active', true)->first();
        }
        if (class_exists('\App\Models\Semester')) {
            $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        }

        return view('auth.dashboard_perpus', [
            'totalBooks' => $totalBooks,
            'totalCopies' => $totalCopies,
            'activeLoans' => $activeLoans,
            'todayLoans' => $todayLoans,
            'returnedLoans' => $returnedLoans,
            'availableCopies' => $availableCopies,
            'booksPerLevel' => $booksPerLevel,
            'recentBooks' => $recentBooks,
            'recentLoans' => $recentLoans,
            'activeAcademicYear' => $activeAcademicYear,
            'activeSemester' => $activeSemester
        ]);
    }

public function walas(Request $request)
{
    $employee = Auth::guard('employee')->user();
    $today = Carbon::today()->toDateString();

    // Get the active academic year
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    // Get the class this homeroom teacher is responsible for
    $class = Classes::where('id_employee', $employee->id_employee)->first();

    if (!$class) {
        return redirect()->back()->with('error', 'Anda tidak memiliki kelas yang ditugaskan.');
    }
  $classId = $class->class_id;
    // Get all students in this class for current academic year
    $students = Student::whereHas('studentSemesters', function($query) use ($class, $activeAcademicYear) {
        $query->where('class_id', $class->class_id)
              ->where('academic_year_id', $activeAcademicYear->id);
    })->get();

    // Count total students
    $totalStudents = $students->count();

    // Get all reports for these students in current academic year and semester
    $reports = Rapor::whereIn('id_student', $students->pluck('id_student'))
                  ->where('academic_year_id', $activeAcademicYear->id)
                  ->where('semester_id', $activeSemester->id)
                  ->get();

    // Count reports
    $uploadedReports = $reports->count();
    $pendingReports = $totalStudents - $uploadedReports;

    // Count reports uploaded today
    $todayReports = Rapor::whereIn('id_student', $students->pluck('id_student'))
                       ->whereDate('report_date', $today)
                       ->count();

    // Get recent reports (last 5)
    $recentReports = Rapor::whereIn('id_student', $students->pluck('id_student'))
                         ->with('student')
                         ->orderBy('report_date', 'desc')
                         ->take(5)
                         ->get();

    // Get students without reports
    $studentsWithReports = $reports->pluck('id_student')->toArray();
    $studentsWithoutReports = $students->whereNotIn('id_student', $studentsWithReports);

    return view('auth.dashboard_walas', compact(
        'totalStudents',
        'uploadedReports',
        'pendingReports',
        'todayReports',
        'recentReports',
        'studentsWithoutReports',
        'class',
        'classId',
        'activeAcademicYear',
        'activeSemester'
    ));
}
}

