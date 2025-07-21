<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Controller
public function index()
{
    $users = Employee::with(['role', 'position']) // Tambahkan 'position'
        ->select('id_employee', 'email', 'fullname', 'role_id', 'position_id')
        ->whereIn('role_id', [2, 3, 4, 5, 1])
        ->orderByRaw("FIELD(role_id, 2, 3, 4, 5, 1)")
        ->orderBy('fullname')
        ->get();

    // Hitung jumlah per role
$roleCounts = [
    'Wali Kelas' => Employee::where('role_id', 1)->count(),
    'Super Admin' => Employee::where('role_id', 2)->count(),
    'Admin Tata Usaha' => Employee::where('role_id', 3)->count(),
    'Admin Pegawai Piket' => Employee::where('role_id', 4)->count(),
    'Admin Perpustakaan' => Employee::where('role_id', 5)->count()
];

    return view('auth.user', compact('users', 'roleCounts'));
}

    // Form Tambah User
    public function create()
    {
        return view('users.create');
    }

    // Simpan User Baru
    public function store(Request $request)
    {
        $request->validate([
            'id_employee' => 'required|unique:employees,id_employee',
            'email' => 'required|email|unique:employees,email',
            'fullname' => 'required|string',
            'role_id' => 'required|integer',
            'password' => 'required|min:6',
        ]);

        Employee::create([
            'id_employee' => $request->id_employee,
            'email' => $request->email,
            'fullname' => $request->fullname,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    // Menampilkan Detail User
    public function show($id)
    {
        $user = Employee::where('id_employee', $id)->firstOrFail();
        return view('users.show', compact('user'));
    }

    // Form Edit User
    public function edit($id)
    {
        $user = Employee::where('id_employee', $id)->firstOrFail();
        return view('users.edit', compact('user'));
    }

    // Update Data User
    public function update(Request $request, $id)
    {
        $user = Employee::where('id_employee', $id)->firstOrFail();

        $request->validate([
            'email' => 'required|email|unique:employees,email,' . $id . ',id_employee',
            'fullname' => 'required|string',
            'role_id' => 'required|integer',
            'password' => 'nullable|min:6',
        ]);

        $user->email = $request->email;
        $user->fullname = $request->fullname;
        $user->role_id = $request->role_id;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    // Hapus User
    public function destroy($id)
    {
        Employee::where('id_employee', $id)->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
