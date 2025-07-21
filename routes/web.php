<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


// Base Route
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes
require __DIR__.'/auth_routes.php';

// Role-based Routes
require __DIR__.'/admin_routes.php';
require __DIR__.'/tu_routes.php';
require __DIR__.'/piket_routes.php';
require __DIR__.'/perpus_routes.php';
require __DIR__.'/walas_routes.php';
// require __DIR__.'/siswa_routes.php';

Route::get('/test', function () {
    return 'Route berhasil!';
});
Route::middleware(['web', 'auth:student'])->group(function () {
    Route::get('/siswa/{id}', [\App\Http\Controllers\StudentController::class, 'showPortal'])
        ->name('students.portal.siswa');
    Route::get('/rapor/download/{id}', [\App\Http\Controllers\RaporController::class, 'download'])
    ->name('rapor.download');
});

// Password Reset
    Route::get('/forgot-password', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\EmployeeLoginController::class, 'resetPassword'])->name('password.update');
