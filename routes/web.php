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
use App\Http\Controllers\ProfileController;


Route::get('/', function () {
    return view('welcome');
});

Route::prefix('login')->group(function () {
    Route::get('/employee', [EmployeeLoginController::class, 'showLoginForm'])->name('login.employee');
    Route::post('/employee', [EmployeeLoginController::class, 'authenticate']);
    Route::get('/forgot-password', [EmployeeLoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [EmployeeLoginController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ProfileController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ProfileController::class, 'resetPassword'])->name('password.update');

    Route::get('/student', [StudentLoginController::class, 'showLoginForm'])->name('login.student');
    Route::post('/student', [StudentLoginController::class, 'login'])->name('login.student.post');
});

Route::post('/logout/employee', [EmployeeLoginController::class, 'logout'])->name('logout.employee');
Route::post('/logout/student', [StudentLoginController::class, 'logout'])->name('logout.student');

Route::middleware(['web', 'auth:employee', 'role:Super Admin'])->group(function () {
     // Dashboards
    Route::get('/admin/dashboard', [DashboardController::class, 'Superadmin'])->name('dashboard.admin');
    // Mengelola siswa
    Route::resource('students', StudentController::class);
    Route::get('/student/search', [StudentAttendanceController::class, 'searchStudent']);
    // Mengelola kelas
    Route::resource('classes', ClassesController::class);
    Route::get('/classes/json/{id}', [ClassesController::class, 'getClassData'])->name('classes.json');
    // Tahun Ajaran & Semester
    Route::resource('academicyear', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
    Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);
    Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.setActive');
    Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
    Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');
    // Hari Libur, dan Status Absensi
    Route::resource('holidays', HolidaysController::class);
    Route::resource('attendance_status', AttendanceStatusController::class);
    // Pegawai dan Absensi Pegawai
    Route::resource('employees', EmployeesController::class);
    Route::resource('attendance', EmployeeAttendanceController::class);
    Route::put('/attendance/{id}', [EmployeeAttendanceController::class, 'update'])->name('attendance.update');
    Route::get('/attendance/export/pdf', [EmployeeAttendanceController::class, 'exportPdf'])->name('attendance.export.pdf');
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
    Route::get('/book-loans/student/{id}', [BookLoanController::class, 'studentLoans'])->name('book-loans.student');
    Route::put('/book-loans/return/{id}', [BookLoanController::class, 'markAsReturned'])->name('book.return');
    Route::put('/book-loans/unreturn/{id}', [BookLoanController::class, 'markAsUnreturned'])->name('book.unreturn');
    Route::get('/book-loans/print/{id_student}', [BookLoanController::class, 'print'])->name('book-loans.print');
    Route::post('/book-loans', [BookLoanController::class, 'store'])->name('book-loans.store');

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
    // Import Excel
});

Route::middleware(['web', 'auth:employee', 'role:Admin Tata Usaha'])->group(function () {
    Route::get('/tu/dashboard', [DashboardController::class, 'TataUsaha'])->name('dashboard.TU');

  // Mengelola siswa
    Route::resource('students', StudentController::class);
    Route::get('/student/search', [StudentAttendanceController::class, 'searchStudent']);
    // Mengelola kelas
    Route::resource('classes', ClassesController::class);
    Route::get('/classes/json/{id}', [ClassesController::class, 'getClassData'])->name('classes.json');
    // Tahun Ajaran & Semester
    Route::resource('academicyear', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
    Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);
    Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.setActive');
    Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
    Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');
    // Hari Libur, dan Status Absensi
    Route::resource('holidays', HolidaysController::class);
    Route::resource('attendance_status', AttendanceStatusController::class);
    // Pegawai dan Absensi Pegawai
    Route::resource('employees', EmployeesController::class);
    Route::resource('attendance', EmployeeAttendanceController::class);
    Route::put('/attendance/{id}', [EmployeeAttendanceController::class, 'update'])->name('attendance.update');
    Route::get('/attendance/export/pdf', [EmployeeAttendanceController::class, 'exportPdf'])->name('attendance.export.pdf');
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
    // Manajemen Pembayaran
    Route::get('/payment/listdata', [PaymentController::class, 'listData'])->name('payment.listdata');
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.store');
    Route::get('/payment/kelola/{id}', [PaymentController::class, 'kelola'])->name('payment.kelola');
    Route::post('/payment/bayar', [PaymentController::class, 'bayar'])->name('payment.bayar');
    Route::post('/payment/batalbayar', [PaymentController::class, 'batalbayar'])->name('payment.batalbayar');
    Route::put('/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/destroy/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
});

Route::middleware(['web', 'auth:employee', 'role:Admin Pegawai Piket'])->group(function () {
    Route::get('/piket/dashboard', [DashboardController::class, 'piket'])->name('dashboard.piket');

    // Hanya absensi siswa dan pegawai
    Route::resource('attendance', EmployeeAttendanceController::class);
    Route::put('/attendance/{id}', [EmployeeAttendanceController::class, 'update'])->name('attendance.update');
    Route::get('/attendance/export/pdf', [EmployeeAttendanceController::class, 'exportPdf'])->name('attendance.export.pdf');
    // Absensi Siswa
    Route::resource('student-attendance', StudentAttendanceController::class);
    Route::get('/students/search', [StudentAttendanceController::class, 'searchById']);
});

Route::middleware(['web', 'auth:employee', 'role:Admin Perpustakaan'])->group(function () {
    Route::get('/perpus/dashboard', [DashboardController::class, 'perpus'])->name('dashboard.perpus');

     // Manajemen Buku dan Peminjaman
    Route::resource('books', BookController::class);
    Route::get('/book-loans', [BookLoanController::class, 'index'])->name('book-loans.index');
    Route::get('/book-loans/classes/{classId}/students', [BookLoanController::class, 'classStudents'])->name('book-loans.class-students');
    Route::get('/book-loans/students/{studentId}/books', [BookLoanController::class, 'studentBooks'])->name('book-loans.student-books');
    Route::get('/book-loans/student/{id}', [BookLoanController::class, 'studentLoans'])->name('book-loans.student');
    Route::put('/book-loans/return/{id}', [BookLoanController::class, 'markAsReturned'])->name('book.return');
    Route::put('/book-loans/unreturn/{id}', [BookLoanController::class, 'markAsUnreturned'])->name('book.unreturn');
    Route::get('/book-loans/print/{id_student}', [BookLoanController::class, 'print'])->name('book-loans.print');
    Route::post('/book-loans', [BookLoanController::class, 'store'])->name('book-loans.store');

});
