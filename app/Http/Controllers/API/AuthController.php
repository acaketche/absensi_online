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
            'id_student' => 'required|exists:students,id_student',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (\Illuminate\Support\Facades\Auth::guard('student')->attempt($request->only('id_student', 'password'))) {
            // Generate a new token for the authenticated user
            $student = \Illuminate\Support\Facades\Auth::guard('student')->user();
            $token = $student->createToken('StudentToken')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'data' => [
                    'token' => $token,
                ]
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
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