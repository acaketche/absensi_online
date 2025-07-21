<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
   DashboardController,
   RaporController,
   EmployeesController

};

Route::middleware(['web', 'auth:employee', 'role:Wali Kelas'])->group(function () {
    Route::get('/walas/dashboard', [DashboardController::class, 'walas'])->name('dashboard.walas');
    Route::post('/profile/update', [EmployeesController::class, 'updateProfile'])->name('employees.profile.update');
    Route::post('/profile/update-password', [EmployeesController::class, 'updatePassword'])->name('employees.profile.update-password');
// Report Cards
    Route::get('/rapor/classes', [RaporController::class, 'classes'])->name('rapor.classes');
    Route::get('/rapor/classes/{classId}/students', [RaporController::class, 'students'])->name('rapor.students');
    Route::get('/rapor/create/{student_id}', [RaporController::class, 'create'])->name('rapor.create');
    Route::post('/rapor', [RaporController::class, 'store'])->name('rapor.store');
    Route::get('/rapor/{id}/edit', [RaporController::class, 'edit'])->name('rapor.edit');
    Route::put('/rapor/{id}', [RaporController::class, 'update'])->name('rapor.update');
    Route::delete('/rapor/{id}', [RaporController::class, 'destroy'])->name('rapor.destroy');
    Route::post('/rapor/upload-massal', [RaporController::class, 'uploadRaporMassalKelas'])->name('rapor.upload-massal');
});
