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
    $roleName = $employee->role->role_name ?? 'Tidak diketahui';

    // Tambahkan log login di sini
  Log::info('Aktivitas login berhasil', [
    'program' => $roleName ?? 'Tidak diketahui',
    'aktivitas' => 'Login ke aplikasi',
    'waktu' => now()->toDateTimeString(),
    'id_employee' => $employee->id_employee,
    'nama' => $employee->fullname ?? '-',
    'ip' => $request->ip(),
]);


    // Redirect berdasarkan role
    if ($roleName === 'Super Admin') {
        return redirect()->route('dashboard.admin');
    } elseif ($roleName === 'Admin Tata Usaha') {
        return redirect()->route('dashboard.TU');
    } elseif ($roleName === 'Admin Pegawai Piket') {
        return redirect()->route('dashboard.piket');
    } elseif ($roleName === 'Admin Perpustakaan') {
        return redirect()->route('dashboard.perpus');
    }

    return redirect()->route('dashboard.default')->with('warning', 'Role tidak dikenali.');
}

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

}
