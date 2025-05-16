<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CheckRole
{
    public function handle(Request $request, Closure $next)
    {
        $employee = Auth::guard('employee')->user();

        Log::info('Employee Authentication Check', [
            'id_employee' => $employee?->id_employee,
            'role_id' => $employee?->role_id,
        ]);

        if ($employee) {
            return $next($request); // ✅ Lanjutkan request
        }

        // Jika belum login, redirect ke login
        return new RedirectResponse(route('login'), 302, ['Error' => 'Silakan login terlebih dahulu.']);
    }
}
