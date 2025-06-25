<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class StudentLoginController extends Controller
{
    /**
     * Tampilkan form login siswa
     */
    public function showLoginForm()
    {
        return view('auth.loginsiswa');
    }

    /**
     * Proses login siswa
     */
    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_student' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil kredensial dan status 'remember'
        $credentials = $request->only('id_student', 'password');
        $remember = $request->filled('remember');

      if (Auth::guard('student')->attempt([
    'id_student' => $request->id_student,
    'password' => $request->password
], $request->remember)) {
    $request->session()->regenerate();
    return redirect()->intended(route('students.show.siswa'));
}
$student = \App\Models\Student::where('id_student', $request->id_student)->first();
if ($student) {
    \Log::info('Password match?', [
        'id' => $student->id_student,
        'match' => Hash::check($request->password, $student->password),
        'input' => $request->password,
        'stored' => $student->password,
    ]);
}
        // Jika gagal login
        Log::warning('Gagal login siswa', ['id_student' => $credentials['id_student']]);

        throw ValidationException::withMessages([
            'id_student' => ['ID Siswa atau password salah.'],
        ]);
    }

    /**
     * Logout siswa
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login');
    }
}
