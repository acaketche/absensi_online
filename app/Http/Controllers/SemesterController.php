<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Support\Facades\DB;

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

 public function update(Request $request, $id)
{
    $request->validate([
        'academic_year_id' => 'nullable|exists:academic_years,id', // Tidak wajib
        'semester_name' => 'required|in:Ganjil,Genap',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'is_active' => 'required|boolean',
    ]);

    DB::beginTransaction();

    try {
        $semester = Semester::findOrFail($id);

        // Ambil academic_year_id yang akan digunakan:
        $academicYearId = $request->academic_year_id ?? $semester->academic_year_id;

        // Jika mengaktifkan semester ini
        if ($request->is_active) {
            // Nonaktifkan semua semester lain
            Semester::where('is_active', 1)->update(['is_active' => 0]);

            // Aktifkan tahun ajaran terkait
            AcademicYear::where('id', $academicYearId)
                ->update(['is_active' => 1]);
        }

        // Update data semester
        $semester->update([
            'academic_year_id' => $academicYearId,
            'semester_name' => $request->semester_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active,
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Semester berhasil diperbarui!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Gagal memperbarui semester: ' . $e->getMessage());
    }
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
