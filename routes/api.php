<?php

use Illuminate\Support\Facades\Route;

Route::post('/login-student', [App\Http\Controllers\Api\AuthController::class, 'login'])->name('api.login.student');


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout-student', [App\Http\Controllers\Api\AuthController::class, 'logout'])->name('api.logout.student');

    Route::get('profile-student', [App\Http\Controllers\Api\StudentController::class, 'profile'])->name('api.profile');
    Route::post('profile-update', [App\Http\Controllers\Api\StudentController::class, 'editProfile'])->name('api.profile.update');

    Route::get('book-loan', [App\Http\Controllers\Api\BooksController::class, 'index'])->name('api.book-loan');

    Route::get('payments-history', [App\Http\Controllers\Api\PaymentsController::class, 'index'])->name('api.payments.history');

    Route::get('student-acchievement', [App\Http\Controllers\Api\StudentAchievementController::class, 'index'])->name('api.student.acchievement');
});

