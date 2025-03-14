<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;

class ClassesController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        $classes = Classes::with('employee')->get();
        return view('classes.classes', compact('classes'));
    }

    // Menampilkan form tambah kelas
    public function create()
    {
        return view('classes.create');
    }

    // Menyimpan data kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'id_employee' => 'nullable|exists:employees,id_employee', // Pastikan id_employee valid
        ]);

        Classes::create($request->all());

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    // Menampilkan detail kelas
    public function show($id)
    {
        $class = Classes::with('teacher')->findOrFail($id);
        return view('classes.show', compact('class'));
    }

    // Menampilkan form edit kelas
    public function edit($id)
    {
        $class = Classes::findOrFail($id);
        return view('classes.edit', compact('class'));
    }

    // Memperbarui data kelas
    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'id_employee' => 'nullable|exists:employees,id_employee',
        ]);

        $class = Classes::findOrFail($id);
        $class->update($request->all());

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui');
    }

    // Menghapus kelas
    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus');
    }
}
