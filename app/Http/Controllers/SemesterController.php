<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\AcademicYear;

class SemesterController extends Controller
{
    // Menampilkan semua semester
    public function index()
    {
        $semesters = Semester::with('academicYear')->get();
        $academicYears = AcademicYear::all(); // Tambahkan ini
        return view('academicyear.index', compact('semesters', 'academicYears'));
    }

    // Menampilkan form tambah semester
    public function create()
    {
        $academicYears = AcademicYear::all();
        return view('semesters.create', compact('academicYears'));
    }

    // Menyimpan semester baru
    public function store(Request $request)
{

    // Validasi input
    $request->validate([
        'academic_year_id' => 'required|exists:academic_years,id',
        'semester_name' => 'required|string',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_active' => 'required|boolean',
    ]);

    // Simpan ke database
    Semester::create([
        'academic_year_id' => $request->academic_year_id,
        'semester_name' => $request->semester_name,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'is_active' => $request->is_active,
    ]);

    return redirect()->back()->with('success', 'Semester berhasil ditambahkan');
}

    // Menampilkan detail semester
    public function show($id)
    {
        $semester = Semester::with('academicYear')->findOrFail($id);
        return view('semesters.show', compact('semester'));
    }

    // Menampilkan form edit semester
    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        $academicYears = AcademicYear::all();
        return view('semesters.edit', compact('semester', 'academicYears'));
    }

    // Memperbarui data semester
    public function update(Request $request, $id)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_name' => 'required|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $semester = Semester::findOrFail($id);
        $semester->update($request->all());

        return redirect()->route('semesters.index')->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete(); // Semua data terkait semester ini akan ikut terhapus

        return back()->with('success', 'Semester berhasil dihapus');
    }

    public function activate($id)
{
    // Nonaktifkan semua semester sebelumnya dalam tahun ajaran yang sama
    $semester = Semester::findOrFail($id);
    Semester::where('academic_year_id', $semester->academic_year_id)
        ->update(['is_active' => 0]);

    // Aktifkan semester yang dipilih
    $semester->is_active = 1;
    $semester->save();

    return back()->with('success', 'Semester berhasil diaktifkan');
}
}
