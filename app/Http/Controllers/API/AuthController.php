<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'id_student' => 'required|exists:students,id_student',
                'password' => 'required|string',
            ]);

            $student = Student::where('id_student', $validated['id_student'])->first();

            if (!Hash::check($validated['password'], $student->password	)) {
                throw ValidationException::withMessages([
                    'id_student' => ['The provided credentials are incorrect.'],
                ]);
            }

            $token = $student->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'data' => [
                    'student' => $student,
                    'token' => $token,
                ],
                'code' => 200,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
                'code' => 401,
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout successful',
            'code' => 200
        ]);
    }
}
