<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\AcademicYear;

class SemesterController extends Controller
{
    // Menampilkan semua semester (sebaiknya dipindah ke AcademicYearController)
    public function index()
    {
        $semesters = Semester::with('academicYear')->latest()->get();
        $academicYears = AcademicYear::all();
        return view('academicyear.index', compact('semesters', 'academicYears'));
    }

    // Menampilkan form tambah semester
    public function create()
    {
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        return view('semesters.create', compact('academicYears'));
    }

    // Menyimpan semester baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_name' => 'required|string|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        // Cek duplikasi semester
        $existing = Semester::where('academic_year_id', $validated['academic_year_id'])
                          ->where('semester_name', $validated['semester_name'])
                          ->exists();

        if ($existing) {
            return back()->withErrors([
                'semester_name' => 'Semester ' . $validated['semester_name'] . ' sudah ada untuk tahun ajaran ini'
            ])->withInput();
        }

        // Jika mengaktifkan semester, nonaktifkan yang lain
        if ($request->is_active) {
            Semester::where('academic_year_id', $validated['academic_year_id'])
                  ->update(['is_active' => false]);
        }

        Semester::create($validated);

        return redirect()->route('academicyear.index')
                       ->with('success', 'Semester berhasil ditambahkan');
    }

    // Menampilkan detail semester
    public function show(Semester $semester)
    {
        return view('semesters.show', compact('semester'));
    }

    // Menampilkan form edit semester
    public function edit(Semester $semester)
    {
        $academicYears = AcademicYear::orderBy('year_name', 'desc')->get();
        return view('semesters.edit', compact('semester', 'academicYears'));
    }

 public function update(Request $request, Semester $semester)
{
    $validated = $request->validate([
        'academic_year_id' => 'nullable|exists:academic_years,id',
        'semester_name' => 'required|in:Ganjil,Genap',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_active' => 'sometimes|boolean',
    ]);

    // Gunakan academic_year_id dari input, atau fallback ke yang lama
    $academicYearId = $validated['academic_year_id'] ?? $semester->academic_year_id;

    // Cek duplikasi semester
    $existing = Semester::where('academic_year_id', $academicYearId)
                      ->where('semester_name', $validated['semester_name'])
                      ->where('id', '!=', $semester->id)
                      ->exists();

    if ($existing) {
        return back()->withErrors([
            'semester_name' => 'Semester ' . $validated['semester_name'] . ' sudah ada untuk tahun ajaran ini'
        ])->withInput();
    }

    // Jika diaktifkan, nonaktifkan semester lain
    if ($request->is_active) {
        Semester::where('academic_year_id', $academicYearId)
              ->where('id', '!=', $semester->id)
              ->update(['is_active' => false]);
    }

    // Update semester
    $semester->update(array_merge($validated, ['academic_year_id' => $academicYearId]));

    return redirect()->route('academicyear.index')
                   ->with('success', 'Semester berhasil diperbarui');
}
    public function destroy($id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete(); // Semua data terkait semester ini akan ikut terhapus

        return back()->with('success', 'Semester berhasil dihapus');
    }

    // Mengaktifkan semester
    public function activate(Semester $semester)
    {
        // Nonaktifkan semua semester dalam tahun ajaran yang sama
        Semester::where('academic_year_id', $semester->academic_year_id)
              ->update(['is_active' => false]);

        // Aktifkan semester yang dipilih
        $semester->update(['is_active' => true]);

        // Aktifkan tahun ajaran terkait jika belum aktif
        $academicYear = $semester->academicYear;
        if (!$academicYear->is_active) {
            $academicYear->update(['is_active' => true]);
        }

        return back()->with('success', 'Semester berhasil diaktifkan');
    }

    // Mendapatkan daftar semester berdasarkan tahun ajaran (API)
    public function getSemesters($academicYearId)
    {
        $semesters = Semester::where('academic_year_id', $academicYearId)
                           ->orderBy('semester_name')
                           ->get();

        return response()->json($semesters);
    }
}
