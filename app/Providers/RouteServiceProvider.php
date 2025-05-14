<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     * (Jika Laravel 8 ke atas, tidak perlu lagi)
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot()
    {
        parent::boot();

        // Menentukan route untuk API
        $this->mapApiRoutes();

        // Menentukan route untuk web
        $this->mapWebRoutes();
    }

    /**
     * Konfigurasi route API
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api') // URL akan diawali dengan /api
            ->middleware('api') // Middleware API (cors, auth, dll)
            ->namespace($this->namespace) // Namespace controller
            ->group(base_path('routes/api.php')); // Mengambil route dari routes/api.php
    }

    /**
     * Konfigurasi route Web
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web') // Middleware untuk web
            ->namespace($this->namespace) // Namespace controller
            ->group(base_path('routes/web.php')); // Mengambil route dari routes/web.php
    }
}
