<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Menampilkan daftar semua siswa
    public function index()
    {
        $students = Student::all();
        $classes = Classes::all();
        $academicYears = AcademicYear::all();
        return view('students.index', compact('students','classes','academicYears'));
    }
    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.detail', compact('student'));
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
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
        ]);

        $data = $request->except('photo');

        // Menyimpan file foto jika diunggah
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/photos', $filename);
            $data['photo'] = $filename;
        }

        Student::create($data);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    // Memperbarui data siswa
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_student' => 'required|string|max:20',
            'fullname' => 'required|string|max:100',
            'class_id' => 'required|string|max:50',
            'parent_phonecell' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
        ]);

        $student = Student::findOrFail($id);
        $data = $request->except('photo');

        // Cek apakah ada foto baru diunggah
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($student->photo) {
                Storage::delete('public/photos/' . $student->photo);
            }

            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/photos', $filename);
            $data['photo'] = $filename;
        }
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']); // Hapus dari array update jika kosong
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    // Menghapus siswa
    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        // Hapus foto dari penyimpanan jika ada
        if ($student->photo) {
            Storage::delete('public/photos/' . $student->photo);
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
