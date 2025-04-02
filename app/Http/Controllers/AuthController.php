<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
{
    $request->validate([
        'id_employee' => 'required|string',
        'password' => 'required|string',
    ]);

    $employee = Employee::where('id_employee', $request->id_employee)->first();

    if (!$employee) {
        return back()->withErrors(['id_employee' => 'NIP tidak ditemukan.'])->withInput();
    }

    if (!Hash::check($request->password, $employee->password)) {
        return back()->withErrors(['password' => 'Password yang dimasukkan salah.'])->withInput();
    }

    // Menentukan apakah user memilih "ingat saya"
    $remember = $request->has('remember');

    Auth::login($employee, $remember); // Login dengan opsi "remember"

    // Redirect berdasarkan role
    switch ($employee->role->role_name) {
        case 'Super Admin':
        case 'Pegawai Tata Usaha':
            return redirect()->route('dashboard.admin');
        case 'Pegawai Piket':
            return redirect()->route('dashboard.piket');
        case 'Pegawai Perpustakaan':
            return redirect()->route('dashboard.perpus');
        default:
            return redirect()->route('dashboard.default');
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function showForgotPasswordForm()
{
    return view('auth.forgot-password');
}

public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:employees,email',
    ]);

    $token = Str::random(64);

    // Simpan token di database manual tanpa migration
    DB::table('password_resets')->insert([
        'email' => $request->email,
        'token' => $token,
        'created_at' => now(),
    ]);

    $resetLink = route('password.reset', ['token' => $token]);

    // Kirim email reset
    Mail::raw("Klik link ini untuk reset password: $resetLink", function ($message) use ($request) {
        $message->to($request->email)
                ->subject('Reset Password');
    });

    return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
}

public function showResetForm($token)
{
    return view('auth.reset-password', ['token' => $token]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:employees,email',
        'password' => 'required|string|min:6|confirmed',
        'token' => 'required'
    ]);

    $reset = DB::table('password_resets')->where([
        ['email', $request->email],
        ['token', $request->token],
    ])->first();

    if (!$reset) {
        return back()->withErrors(['email' => 'Token reset tidak valid.']);
    }

    // Update password di database
    Employee::where('email', $request->email)->update([
        'password' => bcrypt($request->password),
    ]);

    // Hapus token reset setelah digunakan
    DB::table('password_resets')->where('email', $request->email)->delete();

    return redirect('/login')->with('status', 'Password berhasil direset. Silakan login.');
}
}
