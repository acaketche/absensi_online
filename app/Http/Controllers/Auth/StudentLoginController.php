<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;
use Illuminate\Support\Facades\Log;


class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.loginsiswa');
    }

public function authenticate(Request $request)
{
    $request->validate([
        'id_student' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::guard('student')->attempt([
        'id_student' => $request->id_student,
        'password' => $request->password
    ], $request->has('remember'))) {

        $student = Auth::guard('student')->user();

        // Log aktivitas login siswa
        Log::info('Login siswa berhasil', [
            'program' => 'Login',
            'aktivitas' => 'Login ke portal siswa',
            'waktu' => now()->toDateTimeString(),
            'id_student' => $student->id_student,
            'nama' => $student->fullname ?? null,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('students.portal.siswa', ['id' => $student->id_student]);
    }

    return back()->withErrors([
        'id_student' => 'ID siswa atau password salah.'
    ])->withInput();
}

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}
