<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Semester;

class AcademicYearController extends Controller
{
    // Menampilkan semua tahun akademik
    public function index()
    {
        $academicYears = AcademicYear::with('semesters')->get();
        return view('academicyear.index', compact('academicYears'));
    }

    // Menampilkan form tambah tahun akademik
    public function create()
    {
        $academicYears = AcademicYear::all();
        return view('academic_years.create',compact('academicYears'));
    }

    // Menyimpan tahun akademik baru
    public function store(Request $request)
    {
        $request->validate([
            'year_name' => 'required|string|max:20|unique:academic_years,year_name',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $academicYear = AcademicYear::create($request->all());

        return redirect()->route('academicyear.index')->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }

    // Menampilkan detail tahun akademik
    public function show($id)
    {
        $academicYear = AcademicYear::with('semesters')->findOrFail($id);
        return view('academic_years.show', compact('academicYear'));
    }

    // Menampilkan form edit tahun akademik
    public function edit($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        return view('academic_years.edit', compact('academicYear'));
    }

    // Memperbarui data tahun akademik
    public function update(Request $request, $id)
    {
        $request->validate([
            'year_name' => 'required|string|max:20|unique:academic_years,year_name,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);

        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->update($request->all());

        return redirect()->route('academic_years.index')->with('success', 'Tahun Akademik berhasil diperbarui.');
    }

    // Menghapus tahun akademik
    public function destroy($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->delete();

        return redirect()->route('academicyear.index')->with('success', 'Tahun Akademik berhasil dihapus.');
    }
}
