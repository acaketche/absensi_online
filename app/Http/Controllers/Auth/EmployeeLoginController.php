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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use App\Mail\welcomemail;

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

    if (Auth::guard('employee')->attempt([
        'id_employee' => $request->id_employee,
        'password' => $request->password
    ], $request->has('remember'))) {

        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? null;

        Log::info('Aktivitas login berhasil', [
            'program' => 'Login',
            'aktivitas' => 'Login ke aplikasi',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => auth('employee')->id(),
            'role' => $roleName ?? 'Tidak ada role',
            'ip' => $request->ip(),
        ]);

        // Cek berdasarkan role
        if ($roleName === 'Super Admin') {
            return redirect()->route('dashboard.admin');
        } elseif ($roleName === 'Admin Tata Usaha') {
            return redirect()->route('dashboard.TU');
        } elseif ($roleName === 'Admin Pegawai Piket') {
            return redirect()->route('dashboard.piket');
        } elseif ($roleName === 'Admin Perpustakaan') {
            return redirect()->route('dashboard.perpus');
        } elseif ($roleName === 'Wali Kelas') {
            return redirect()->route('dashboard.walas');
        }

        // Jika tidak memiliki role, cek apakah ada di jadwal piket
        $hasPiketSchedule = \App\Models\PicketSchedule::where('employee_id', $employee->id_employee)->exists();

        if ($hasPiketSchedule) {
            return redirect()->route('dashboard.piket');
        }

        // Jika tidak memiliki role dan tidak ada di jadwal piket
        return redirect()->route('dashboard.default')->with('warning', 'Role tidak dikenali dan tidak memiliki jadwal piket.');
    }

    Log::warning('Login gagal', [
        'id_employee' => $request->id_employee,
        'ip' => $request->ip(),
        'waktu' => now()->toDateTimeString(),
    ]);

    return back()->withErrors([
        'id_employee' => 'NIP atau password salah.'
    ])->withInput();
}


 public function logout(Request $request)
    {
        Auth::guard('employee')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login/employee');
    }
// Tampilkan form untuk lupa password
public function showForgotPasswordForm()
{
    return view('auth.forgot-password');
}

public function sendResetLinkEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:employees,email',
    ], [
        'email.exists' => 'Email tidak ditemukan di sistem kami.',
    ]);

    $user = Employee::where('email', $request->email)->first();

    $token = Str::random(60);

    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        [
            'email' => $user->email,
            'token' => bcrypt($token),
            'created_at' => now()
        ]
    );

    Mail::to($user->email)->send(new welcomemail($token, $user->email));

    return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
}

// Tampilkan form reset password (dari email)
public function showResetForm(Request $request, $token = null)
{
    return view('auth.reset_password')->with([
        'token' => $token,
        'email' => $request->email,
    ]);
}

// Simpan password baru ke database
public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email|exists:employees,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $status = Password::broker('employees')->reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($employee, $password) {
            $employee->password = Hash::make($password);
            $employee->setRememberToken(Str::random(60));
            $employee->save();

            event(new PasswordReset($employee));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login.employee')->with('status', __($status))
        : back()->withErrors(['email' => [__($status)]]);
}

}
