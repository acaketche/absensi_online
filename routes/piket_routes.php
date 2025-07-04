<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    EmployeeAttendanceController,
    StudentAttendanceController
};

Route::middleware(['web', 'auth:employee', 'role:Admin Pegawai Piket'])->group(function () {
    Route::get('/piket/dashboard', [DashboardController::class, 'piket'])->name('dashboard.piket');

    // Employee Attendance
    Route::resource('attendance', EmployeeAttendanceController::class);

    // Student Attendance
    Route::resource('student-attendance', StudentAttendanceController::class);
});
