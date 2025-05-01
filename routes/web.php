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

Route::get('/', function () {
    return view('welcome');
});

// Resource routes
Route::resource('students', StudentController::class);
Route::resource('classes', ClassesController::class);
Route::resource('academicyear', AcademicYearController::class);
Route::resource('semesters', SemesterController::class);
Route::resource('subjects', SubjectController::class);
Route::resource('holidays', HolidaysController::class);
Route::resource('employees', EmployeesController::class);
Route::resource('student-attendance', StudentAttendanceController::class);
Route::resource('Rapor', RaporController::class);
Route::get('/payment/listdata', [PaymentController::class, 'listData'])->name('payment.listdata');
Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.store');
Route::get('/payment/kelola/{id}', [PaymentController::class, 'kelola'])->name('payment.kelola');
Route::post('/payment/bayar', [PaymentController::class, 'bayar'])->name('payment.bayar');
Route::post('/payment/batalbayar', [PaymentController::class, 'batalbayar'])->name('payment.batalbayar');
Route::put('/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
Route::delete('/payment/destroy/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');

// Mengubah status tahun ajaran & semester
Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);

use App\Http\Controllers\BookController;
use App\Http\Controllers\BookLoanController;

Route::resource('books', BookController::class);
Route::resource('book-loans', BookLoanController::class);
Route::get('/books/kelas-siswa', [BookLoanController::class, 'kelasSiswa'])->name('book-loans.kelas-siswa');

Route::get('/api/class/{id}/students', function ($id) {
    $class = \App\Models\Kelas::findOrFail($id);
    $students = \App\Models\Student::where('class_id', $id)->get();
    return response()->json([
        'class_name' => $class->name,
        'students' => $students
    ]);
});

Route::get('/api/student/{id}/loans', function ($id) {
    $loans = \App\Models\BookLoan::with('book')->where('id_student', $id)->get();
    return response()->json($loans);
});


    Route::resource('users', UserController::class);
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/dashboard/piket', [DashboardController::class, 'piket'])->name('dashboard.piket');
    Route::get('/dashboard/perpus', [DashboardController::class, 'perpus'])->name('dashboard.perpus');
    Route::get('/dashboard/default', [DashboardController::class, 'default'])->name('dashboard.default');

Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.setActive');
Route::resource('attendance', EmployeeAttendanceController::class);
Route::resource('attendance_status', AttendanceStatusController::class);


Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');

use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\Auth\StudentLoginController;

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

// Dashboard masing-masing
Route::middleware(['employee'])->group(function () {
    Route::get('/dashboard/employee', function () {
        return view('dashboard.employee');
    })->name('dashboard.employee');
});

Route::middleware(['student'])->group(function () {
    Route::get('/dashboard/student', function () {
        return view('dashboard.student');
    })->name('dashboard.student');
});

Route::get('/search-student', [StudentController::class, 'search'])->name('search.student');
