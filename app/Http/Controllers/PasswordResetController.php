<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function index()
    {
        $passwordResets = PasswordReset::all();
        return response()->json($passwordResets);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $passwordReset = PasswordReset::create([
            'email' => $request->email,
            'token' => $request->token,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Token reset password berhasil disimpan', 'data' => $passwordReset]);
    }

    public function show($email)
    {
        $passwordReset = PasswordReset::where('email', $email)->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($passwordReset);
    }

    public function destroy($email)
    {
        $passwordReset = PasswordReset::where('email', $email)->delete();

        return response()->json(['message' => 'Token reset password berhasil dihapus']);
    }
}
