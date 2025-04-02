<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceStatus;

class AttendanceStatusController extends Controller
{
    public function index()
    {
        $statuses = AttendanceStatus::orderBy('created_at', 'desc')->get();
        return view('attendance_status.index', compact('statuses'));
    }

    public function create()
    {
        return view('attendance_status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_name' => 'required|string|max:255'
        ]);

        AttendanceStatus::create([
            'status_name' => $request->status_name
        ]);

        return redirect()->route('attendance_status.index')->with('success', 'Status Kehadiran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $status = AttendanceStatus::findOrFail($id);
        return view('attendance_status.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status_name' => 'required|string|max:255'
        ]);

        $status = AttendanceStatus::findOrFail($id);
        $status->update([
            'status_name' => $request->status_name
        ]);

        return redirect()->route('attendance_status.index')->with('success', 'Status Kehadiran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $status = AttendanceStatus::findOrFail($id);
        $status->delete();

        return redirect()->route('attendance_status.index')->with('success', 'Status Kehadiran berhasil dihapus.');
    }
}
