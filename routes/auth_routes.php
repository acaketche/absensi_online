<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EmployeeLoginController;
use App\Http\Controllers\Auth\StudentLoginController;
use App\Http\Controllers\ProfileController;

Route::prefix('login')->group(function () {
    // Employee Auth
    Route::get('/employee', [EmployeeLoginController::class, 'showLoginForm'])->name('login.employee');
    Route::post('/employee', [EmployeeLoginController::class, 'authenticate']);

    // Student Auth
    Route::get('/student', [StudentLoginController::class, 'showLoginForm'])->name('login.student');
    Route::post('/student', [StudentLoginController::class, 'authenticate']);
});

// Logout Routes
Route::post('/logout/employee', [EmployeeLoginController::class, 'logout'])->name('logout.employee');
Route::post('/logout/student', [StudentLoginController::class, 'logout'])->name('logout.student');

