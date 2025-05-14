<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\HolidaysController;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\AttendanceStatusController;
use App\Http\Controllers\RaporController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLoanController;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\Auth\StudentLoginController;


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('login')->group(function () {
    Route::get('/employee', [EmployeeLoginController::class, 'showLoginForm'])->name('login.employee');
    Route::post('/employee', [EmployeeLoginController::class, 'authenticate']);
    Route::get('/forgot-password', [EmployeeLoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [EmployeeLoginController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [EmployeeLoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [EmployeeLoginController::class, 'resetPassword'])->name('password.update');

    Route::get('/student', [StudentLoginController::class, 'showLoginForm'])->name('login.student');
    Route::post('/student', [StudentLoginController::class, 'login']);
});

Route::post('/logout/employee', [EmployeeLoginController::class, 'logout'])->name('logout.employee');
Route::post('/logout/student', [StudentLoginController::class, 'logout'])->name('logout.student');

Route::middleware(['web', 'auth:employee', 'role:2'])->group(function () {
     // Dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'Superadmin'])->name('dashboard.admin');
    // Mengelola siswa
    Route::resource('students', StudentController::class);
    // Mengelola kelas
    Route::resource('classes', ClassesController::class);
    // Tahun Ajaran & Semester
    Route::resource('academicyear', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
    Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);
    Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.setActive');
    Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
    Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');
    // Mata Pelajaran, Hari Libur, dan Status Absensi
    Route::resource('subjects', SubjectController::class);
    Route::resource('holidays', HolidaysController::class);
    Route::resource('attendance_status', AttendanceStatusController::class);
    // Pegawai dan Absensi Pegawai
    Route::resource('employees', EmployeesController::class);
    Route::resource('attendance', EmployeeAttendanceController::class);
    // Absensi Siswa
    Route::resource('student-attendance', StudentAttendanceController::class);
    Route::get('/students/search', [StudentAttendanceController::class, 'searchById']);
    // Rapor
    Route::get('/rapor/classes', [RaporController::class, 'classes'])->name('rapor.classes');
    Route::get('/rapor/classes/{classId}/students', [RaporController::class, 'students'])->name('rapor.students');
    Route::get('/rapor/create', [RaporController::class, 'create'])->name('rapor.create');
    Route::post('/rapor', [RaporController::class, 'store'])->name('rapor.store');
    Route::get('/rapor/{id}/edit', [RaporController::class, 'edit'])->name('rapor.edit');
    Route::put('/rapor/{id}', [RaporController::class, 'update'])->name('rapor.update');
    Route::delete('/rapor/{id}', [RaporController::class, 'destroy'])->name('rapor.destroy');
    // Manajemen Buku dan Peminjaman
    Route::resource('books', BookController::class);
    Route::get('/book-loans', [BookLoanController::class, 'index'])->name('book-loans.index');
    Route::get('/book-loans/classes/{classId}/students', [BookLoanController::class, 'classStudents'])->name('book-loans.class-students');
    Route::get('/book-loans/students/{studentId}/books', [BookLoanController::class, 'studentBooks'])->name('book-loans.student-books');
    // Manajemen Pembayaran
    Route::get('/payment/listdata', [PaymentController::class, 'listData'])->name('payment.listdata');
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.store');
    Route::get('/payment/kelola/{id}', [PaymentController::class, 'kelola'])->name('payment.kelola');
    Route::post('/payment/bayar', [PaymentController::class, 'bayar'])->name('payment.bayar');
    Route::post('/payment/batalbayar', [PaymentController::class, 'batalbayar'])->name('payment.batalbayar');
    Route::put('/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/destroy/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
    // Manajemen User
    Route::resource('users', UserController::class);
});

Route::middleware(['web', 'auth:employee', 'role:3'])->group(function () {
    Route::get('/tu/dashboard', [DashboardController::class, 'TataUsaha'])->name('dashboard.TU');

    // Semua route resource di luar book & book-loans bisa dimasukkan di sini
    Route::resource('students', StudentController::class);
    Route::resource('classes', ClassesController::class);
    Route::resource('academicyear', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('holidays', HolidaysController::class);
    Route::resource('employees', EmployeesController::class);
    Route::resource('student-attendance', StudentAttendanceController::class);
    Route::resource('users', UserController::class);
    Route::resource('attendance', EmployeeAttendanceController::class);
    Route::resource('attendance_status', AttendanceStatusController::class);

    // Payment routes
    Route::get('/payment/listdata', [PaymentController::class, 'listData'])->name('payment.listdata');
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.store');
    Route::get('/payment/kelola/{id}', [PaymentController::class, 'kelola'])->name('payment.kelola');
    Route::post('/payment/bayar', [PaymentController::class, 'bayar'])->name('payment.bayar');
    Route::post('/payment/batalbayar', [PaymentController::class, 'batalbayar'])->name('payment.batalbayar');
    Route::put('/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/destroy/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');

    // Rapor
    Route::get('/rapor/classes', [RaporController::class, 'classes'])->name('rapor.classes');
    Route::get('/rapor/classes/{classId}/students', [RaporController::class, 'students'])->name('rapor.students');
    Route::get('/rapor/create', [RaporController::class, 'create'])->name('rapor.create');
    Route::post('/rapor', [RaporController::class, 'store'])->name('rapor.store');
    Route::get('/rapor/{id}/edit', [RaporController::class, 'edit'])->name('rapor.edit');
    Route::put('/rapor/{id}', [RaporController::class, 'update'])->name('rapor.update');
    Route::delete('/rapor/{id}', [RaporController::class, 'destroy'])->name('rapor.destroy');

    // Toggle status
    Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
    Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');
    Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
    Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);
});

Route::middleware(['web', 'auth:employee', 'role:4'])->group(function () {
    Route::get('/piket/dashboard', [DashboardController::class, 'piket'])->name('dashboard.piket');

    // Hanya absensi siswa dan pegawai
    Route::resource('student-attendance', StudentAttendanceController::class);
    Route::resource('attendance', EmployeeAttendanceController::class);
});

Route::middleware(['web', 'auth:employee', 'role:5'])->group(function () {
    Route::get('/perpus/dashboard', [DashboardController::class, 'perpus'])->name('dashboard.perpus');

    // Manajemen buku
    Route::resource('books', BookController::class);

    // Manajemen peminjaman buku
    Route::get('/book-loans', [BookLoanController::class, 'index'])->name('book-loans.index');
    Route::get('/book-loans/classes/{classId}/students', [BookLoanController::class, 'classStudents'])->name('book-loans.class-students');
    Route::get('/book-loans/students/{studentId}/books', [BookLoanController::class, 'studentBooks'])->name('book-loans.student-books');
});
