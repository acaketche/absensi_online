<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    EmployeesController,
    StudentAttendanceController,
    AttendanceStatusController
};

Route::middleware(['web', 'auth:employee', 'role:Admin Pegawai Piket'])->group(function () {
    Route::get('/piket/dashboard', [DashboardController::class, 'piket'])->name('dashboard.piket');

    // Employee Attendance
     Route::resource('attendance_status', AttendanceStatusController::class);

    // Employee Management
    Route::post('/profile/update', [EmployeesController::class, 'updateProfile'])->name('employees.profile.update');
    Route::post('/profile/update-password', [EmployeesController::class, 'updatePassword'])->name('employees.profile.update-password');

    // Student Attendance
    Route::resource('student-attendance', StudentAttendanceController::class);
    Route::get('/student/search', [StudentAttendanceController::class, 'search']);
    Route::get('/student-attendance/export/pdf', [StudentAttendanceController::class, 'exportPdf'])
     ->name('student-attendance.export.pdf');
});
