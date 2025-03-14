<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidaysController extends Controller
{
    // Menampilkan daftar hari libur
    public function index()
    {
        $holidays = Holiday::with('academicYear')->get();
        return view('holidays.holidays', compact('holidays'));
    }

    // Menampilkan form tambah hari libur
    public function create()
    {
        return view('holidays.create');
    }

    // Menyimpan data hari libur
    public function store(Request $request)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'description' => 'required|string|max:255',
            'academic_year_id' => 'required|integer'
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil ditambahkan');
    }

    // Menampilkan detail hari libur
    public function show($id)
    {
        $holiday = Holiday::with('academicYear')->findOrFail($id);
        return view('holidays.show', compact('holiday'));
    }

    // Menampilkan form edit hari libur
    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    // Memperbarui data hari libur
    public function update(Request $request, $id)
    {
        $request->validate([
            'holiday_date' => 'required|date',
            'description' => 'required|string|max:255',
            'academic_year_id' => 'required|integer'
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil diperbarui');
    }

    // Menghapus hari libur
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return redirect()->route('holidays.index')->with('success', 'Hari libur berhasil dihapus');
    }
}
