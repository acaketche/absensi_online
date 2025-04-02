<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.student-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('id_student', 'password');

        if (Auth::guard('student')->attempt($credentials)) {
            return redirect()->route('dashboard.student');
        }

        return back()->withErrors(['error' => 'ID atau password salah.']);
    }

    public function logout()
    {
        Auth::guard('student')->logout();
        return redirect('/login/student');
    }
}
