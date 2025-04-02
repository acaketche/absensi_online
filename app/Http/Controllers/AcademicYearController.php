<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;

class AcademicYearController extends Controller
{
    // Menampilkan semua tahun akademik
    public function index()
    {
        $academicYears = AcademicYear::all();
        return view('academicyear.index', compact('academicYears'));
    }

    // Menampilkan form tambah tahun akademik
    public function create()
    {
        $academicYears = AcademicYear::select('year_name')->distinct()->get();
        return view('academic_years.create', compact('academicYears'));
    }
        public function show($id)
    {
        $academicYears = AcademicYear::findOrFail($id);
        return view('academicyear.index', compact('academicYears'));
    }

    // Menyimpan tahun akademik baru
    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'year_name' => 'required|string|max:20|unique:academic_years',
            'semester' => 'required|in:Ganjil,Genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean',
        ]);
        AcademicYear::create($request->all());

        return redirect()->route('academic_years.index')->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }

    // Menampilkan form edit tahun akademik
    public function edit($id)
    {
        $academicYears = AcademicYear::with('semesters')->get();
        return view('academic_years.edit', compact('academicYear'));
    }

    // Memperbarui data tahun akademik
    public function update(Request $request, $id)
    {
        $request->validate([
            'year_name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'required|boolean',
        ]);

        // 2️⃣ Cari Data Tahun Ajaran
        $academicYear = AcademicYear::find($id);
        if (!$academicYear) {
            return back()->with('error', 'Tahun Ajaran tidak ditemukan!');
        }

        // 3️⃣ Update Data
        $academicYear->update([
            'year_name' => $request->year_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->is_active,
        ]);

        return redirect()->back()->with('success', 'Tahun Ajaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->delete(); // Menghapus semua data terkait karena ON DELETE CASCADE

        return back()->with('success', 'Tahun Ajaran berhasil dihapus');
    }


    public function activate($id)
    {
        // Nonaktifkan semua tahun ajaran sebelumnya
        AcademicYear::query()->update(['is_active' => 0]);

        // Aktifkan tahun ajaran yang dipilih
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->is_active = 1;
        $academicYear->save();

        return back()->with('success', 'Tahun Ajaran berhasil diaktifkan');
    }
}
