<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
public function login(Request $request)
    {
        $request->validate([
            'id_student' => 'required|string',
            'password' => 'required|string',
        ]);

        \Log::info('Login attempt for id_student: ' . $request->id_student);

        // Cari data student berdasarkan id_student
        $student = Student::where('id_student', $request->id_student)->first();

        if ($student) {
            \Log::info('Student found: ' . $student->id_student);
            \Log::info('Stored password hash: ' . $student->password);
            $passwordMatch = Hash::check($request->password, $student->password);
            \Log::info('Password match: ' . ($passwordMatch ? 'true' : 'false'));

        } else {
            \Log::info('Student not found');
        }

        // Cek jika student tidak ditemukan atau password salah
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        if (!Hash::check($request->password, $student->password)) {
            return response()->json(['message' => 'Password incorrect'], 401);
        }

        // Jika login berhasil, kembalikan data student
        return response()->json([
            'message' => 'Login berhasil',
            'student' => $student,
        ]);
    }
}
