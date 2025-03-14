<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    // Menampilkan semua data subject
    public function index()
    {
        $subjects = Subject::all();
        return view('subject.subject', compact('subjects'));
    }

    // Menampilkan form tambah subject
    public function create()
    {
        return view('subjects.create');
    }

    // Menyimpan subject baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255'
        ]);

        Subject::create([
            'subject_name' => $request->subject_name
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject berhasil ditambahkan');
    }

    // Menampilkan form edit subject
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('subjects.edit', compact('subject'));
    }

    // Update subject di database
    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255'
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update([
            'subject_name' => $request->subject_name
        ]);

        return redirect()->route('subjects.index')->with('success', 'Subject berhasil diperbarui');
    }

    // Menghapus subject
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject berhasil dihapus');
    }
}
