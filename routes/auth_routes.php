<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\ProfileController;

Route::prefix('login')->group(function () {
    // Employee Auth
    Route::get('/employee', [EmployeeLoginController::class, 'showLoginForm'])->name('login.employee');
    Route::post('/employee', [EmployeeLoginController::class, 'authenticate']);

    // Password Reset
    Route::get('/forgot-password', [EmployeeLoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [EmployeeLoginController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ProfileController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ProfileController::class, 'resetPassword'])->name('password.update');

    // Student Auth
    Route::get('/student', [StudentLoginController::class, 'showLoginForm'])->name('login.student');
    Route::post('/student', [StudentLoginController::class, 'login'])->name('login.student.post');
});

// Logout Routes
Route::post('/logout/employee', [EmployeeLoginController::class, 'logout'])->name('logout.employee');
Route::post('/logout/student', [StudentLoginController::class, 'logout'])->name('logout.student');
