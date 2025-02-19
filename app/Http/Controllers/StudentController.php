<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    // Menampilkan daftar semua siswa
    public function index()
    {
        $students = Student::all();
        return view('students.index', compact('students'));
    }

    // Menampilkan form untuk menambah siswa baru
    public function create()
    {
        return view('students.create');
    }

    // Menyimpan siswa baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'id_student' => 'required|string|max:20',
            'fullname' => 'required|string|max:100',
            'class_id' => 'required|string|max:50',
            'parent_phonecell' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:6',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
        ]);

        // Menyimpan data siswa baru
        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    // Menampilkan detail siswa
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    // Menampilkan form untuk mengedit siswa
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('students.edit', compact('student'));
    }

    // Memperbarui data siswa
    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'class_id' => 'required|string|max:255',
            'parent_phonecell' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
        ]);

        $student = Student::findOrFail($id);
        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    // Menghapus siswa
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
