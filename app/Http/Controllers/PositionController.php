<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    // Menampilkan semua posisi
    public function index()
    {
        $positions = Position::all();
        return view('position.position', compact('positions'));
    }

    // Menampilkan form tambah posisi
    public function create()
    {
        return view('positions.create');
    }

    // Menyimpan posisi baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Position::create([
            'name' => $request->name
        ]);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    // Menampilkan form edit posisi
    public function edit($id)
    {
        $position = Position::findOrFail($id);
        return view('positions.edit', compact('position'));
    }

    // Menyimpan update posisi
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $position = Position::findOrFail($id);
        $position->update([
            'name' => $request->name
        ]);

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    // Menghapus posisi
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return redirect()->route('positions.index')->with('success', 'Jabatan berhasil dihapus.');
    }
}
