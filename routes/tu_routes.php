<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    StudentController,
    ClassesController,
    SemesterController,
    PaymentController,
    EmployeesController
};

Route::middleware(['web', 'auth:employee', 'role:Admin Tata Usaha'])->group(function () {
    Route::get('/tu/dashboard', [DashboardController::class, 'TataUsaha'])->name('dashboard.TU');
    Route::post('/profile/update', [EmployeesController::class, 'updateProfile'])->name('employees.profile.update');
    Route::post('/profile/update-password', [EmployeesController::class, 'updatePassword'])->name('employees.profile.update-password');
  // Student Management
    Route::resource('students', StudentController::class);
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/import/template', [StudentController::class, 'showTemplate'])->name('students.template.page');
    Route::get('/template-siswa-kosong', [StudentController::class, 'downloadTemplateEmpty'])->name('students.template.empty');
    Route::get('/template-siswa-isi', [StudentController::class, 'downloadTemplateFilled'])->name('students.template.filled');
    Route::post('/students/upload-media', [StudentController::class, 'uploadMediaZip'])->name('students.uploadMediaZip');
    Route::get('/get-semesters/{academicYearId}', [SemesterController::class, 'getSemesters'])->name('ajax.semesters');
    Route::get('/get-classes/{academicYearId}', [ClassesController::class, 'getClasses'])->name('ajax.classes');

    // Payment Management
    Route::get('/payment/listdata', [PaymentController::class, 'listData'])->name('payment.listdata');
    Route::get('/payment/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payment/create', [PaymentController::class, 'create'])->name('payment.store');
    Route::get('/payment/kelola/{id}', [PaymentController::class, 'kelola'])->name('payment.kelola');
    Route::post('/payment/bayar', [PaymentController::class, 'bayar'])->name('payment.bayar');
    Route::post('/payment/batalbayar', [PaymentController::class, 'batalbayar'])->name('payment.batalbayar');
    Route::put('/payment/update/{id}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payment/destroy/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
    Route::get('/payments/template/{id}', [PaymentController::class, 'downloadTemplate'])->name('payment.template');
    Route::post('/payments/import/{sppId}', [PaymentController::class, 'import'])->name('payments.import');
     Route::get('/payments/export/all', [PaymentController::class, 'exportAll'])
        ->name('payments.export.all');

    Route::get('/payments/export/{grade}', [PaymentController::class, 'exportByGrade'])
        ->name('payments.export.grade');
});
