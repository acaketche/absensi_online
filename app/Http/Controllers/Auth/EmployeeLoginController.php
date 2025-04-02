<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EmployeeLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Nama file login harus sesuai
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'id_employee' => 'required|string',
            'password' => 'required|string',
        ]);

        // Menggunakan attempt() untuk autentikasi dengan guard employee
        if (Auth::guard('employee')->attempt([
            'id_employee' => $request->id_employee,
            'password' => $request->password
        ], $request->has('remember'))) {

            $employee = Auth::guard('employee')->user();

            if ($employee->role->role_name === 'Super Admin' || $employee->role->role_name === 'Pegawai Tata Usaha') {
                return redirect()->route('dashboard.admin');
            } elseif ($employee->role->role_name === 'Pegawai Piket') {
                return redirect()->route('dashboard.piket');
            } elseif ($employee->role->role_name === 'Pegawai Perpustakaan') {
                return redirect()->route('dashboard.perpus');
            }

            // Jika role tidak dikenal, arahkan ke dashboard admin atau kembali ke login
            return redirect()->route('dashboard.admin')->with('warning', 'Role tidak dikenali, diarahkan ke admin.');
        }

        // Jika login gagal
        return back()->withErrors(['id_employee' => 'NIP atau password salah.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login/employee');
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

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $resetLink = route('password.reset', ['token' => $token]);

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
