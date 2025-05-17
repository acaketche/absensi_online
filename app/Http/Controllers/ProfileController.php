<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function show()
    {
        $employee = Auth::guard('employee')->user();
        return view('profile.show', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = Auth::guard('employee')->user();

        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,'.$employee->id_employee.',id_employee',
            'phone_number' => 'required|string|max:20',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['fullname', 'email', 'phone_number']);

        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($employee->photo) {
                \Storage::delete('public/' . $employee->photo);
            }

            // Guardar nueva foto
            $photoPath = $request->file('photo')->store('photo_pegawai', 'public');
            $data['photo'] = $photoPath;
        }

        $employee->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $employee = Auth::guard('employee')->user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $employee->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
        }

        // Actualizar contraseña
        $employee->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah.');
    }
}
