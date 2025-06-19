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
    PaymentController
};

Route::middleware(['web', 'auth:employee', 'role:Admin Tata Usaha'])->group(function () {
    Route::get('/tu/dashboard', [DashboardController::class, 'TataUsaha'])->name('dashboard.TU');

    // Mengelola siswa
   Route::resource('students', StudentController::class);
    Route::get('/student/search', [StudentAttendanceController::class, 'searchStudent']);
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
    Route::get('/students/import/template', [StudentController::class, 'showTemplate'])->name('students.template.page');
    Route::get('/students/import/template/download', [StudentController::class, 'downloadTemplate'])->name('students.template.download');

    // Mengelola kelas
    Route::resource('classes', ClassesController::class);
    Route::get('/classes/json/{id}', [ClassesController::class, 'getClassData'])->name('classes.json');

    // Tahun Ajaran & Semester
    Route::resource('academicyear', AcademicYearController::class);
    Route::resource('semesters', SemesterController::class);
    Route::post('/academic-year/toggle-status/{id}', [StatusController::class, 'toggleAcademicYearStatus']);
    Route::post('/semester/toggle-status/{id}', [StatusController::class, 'toggleSemesterStatus']);
    Route::post('/academic-years/{id}/set-active', [AcademicYearController::class, 'setActive'])->name('academic_years.setActive');
    Route::post('/academic-year/activate/{id}', [AcademicYearController::class, 'activate'])->name('academic-year.activate');
    Route::post('/semester/activate/{id}', [SemesterController::class, 'activate'])->name('semester.activate');

    // Hari Libur, dan Status Absensi
    Route::resource('holidays', HolidaysController::class);

    // Rapor
    Route::get('/rapor/classes', [RaporController::class, 'classes'])->name('rapor.classes');
    Route::get('/rapor/classes/{classId}/students', [RaporController::class, 'students'])->name('rapor.students');
    Route::get('/rapor/create/{student_id}', [RaporController::class, 'create'])->name('rapor.create');
    Route::post('/rapor', [RaporController::class, 'store'])->name('rapor.store');
    Route::get('/rapor/{id}/edit', [RaporController::class, 'edit'])->name('rapor.edit');
    Route::put('/rapor/{id}', [RaporController::class, 'update'])->name('rapor.update');
    Route::delete('/rapor/{id}', [RaporController::class, 'destroy'])->name('rapor.destroy');

    // Manajemen Pembayaran
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
