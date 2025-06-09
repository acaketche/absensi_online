<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    StudentController,
    ClassesController,
    AcademicYearController,
    SemesterController,
    StatusController,
    HolidaysController,
    AttendanceStatusController,
    EmployeesController,
    EmployeeAttendanceController,
    StudentAttendanceController,
    RaporController,
    BookController,
    BookLoanController,
    PaymentController,
    UserController
};

Route::middleware(['web', 'auth:employee', 'role:Super Admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'Superadmin'])->name('dashboard.admin');

    // Student Management
    Route::resource('students', StudentController::class);
    Route::get('/student/search', [StudentAttendanceController::class, 'searchStudent']);
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/import/template', [StudentController::class, 'showTemplate'])->name('students.template.page');
    Route::get('/students/import/template/download', [StudentController::class, 'downloadTemplate'])->name('students.template.download');

    // Class Management
    Route::resource('classes', ClassesController::class);
    Route::get('/classes/json/{id}', [ClassesController::class, 'getClassData'])->name('classes.json');

    // Academic Management
    Route::resource('academicyear', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
    Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);
    Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.setActive');
    Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
    Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');

    // Holidays & Attendance Status
    Route::resource('holidays', HolidaysController::class);
    Route::resource('attendance_status', AttendanceStatusController::class);

    // Employee Management
    Route::resource('employees', EmployeesController::class);
    Route::resource('attendance', EmployeeAttendanceController::class);

    // Student Attendance
    Route::resource('student-attendance', StudentAttendanceController::class);
    Route::get('/students/search', [StudentAttendanceController::class, 'searchById']);

    // Report Cards
    Route::get('/rapor/classes', [RaporController::class, 'classes'])->name('rapor.classes');
    Route::get('/rapor/classes/{classId}/students', [RaporController::class, 'students'])->name('rapor.students');
    Route::get('/rapor/create/{student_id}', [RaporController::class, 'create'])->name('rapor.create');
    Route::post('/rapor', [RaporController::class, 'store'])->name('rapor.store');
    Route::get('/rapor/{id}/edit', [RaporController::class, 'edit'])->name('rapor.edit');
    Route::put('/rapor/{id}', [RaporController::class, 'update'])->name('rapor.update');
    Route::delete('/rapor/{id}', [RaporController::class, 'destroy'])->name('rapor.destroy');

    // Library Management
    Route::resource('books', BookController::class);

    // Book Loans
    Route::get('/book-loans', [BookLoanController::class, 'index'])->name('book-loans.index');
    Route::get('/book-loans/classes/{classId}/students', [BookLoanController::class, 'classStudents'])->name('book-loans.class-students');
    Route::get('/book-loans/students/{studentId}/books', [BookLoanController::class, 'studentBooks'])->name('book-loans.student-books');
    Route::get('/book-loans/student/{id}', [BookLoanController::class, 'studentLoans'])->name('book-loans.student');
    Route::put('/book-loans/return/{id}', [BookLoanController::class, 'markAsReturned'])->name('book.return');
    Route::put('/book-loans/unreturn/{id}', [BookLoanController::class, 'markAsUnreturned'])->name('book.unreturn');
    Route::get('/book-loans/print/{id_student}', [BookLoanController::class, 'print'])->name('book-loans.print');
    Route::post('/book-loans', [BookLoanController::class, 'store'])->name('book-loans.store');

    // Payment Management
    Route::get('/payment/listdata', [PaymentController::class, 'listData'])->name('payment.listdata');
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.store');
    Route::get('/payment/kelola/{id}', [PaymentController::class, 'kelola'])->name('payment.kelola');
    Route::post('/payment/bayar', [PaymentController::class, 'bayar'])->name('payment.bayar');
    Route::post('/payment/batalbayar', [PaymentController::class, 'batalbayar'])->name('payment.batalbayar');
    Route::put('/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/destroy/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');

    // User Management
    Route::resource('users', UserController::class);
});
