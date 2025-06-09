<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/test', function () {
    return 'Route berhasil!';
});
