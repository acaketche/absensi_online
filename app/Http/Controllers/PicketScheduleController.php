<?php

namespace App\Http\Controllers;

use App\Models\PicketSchedule;
use App\Models\Employee;
use App\Models\Classes;
use Illuminate\Http\Request;

class PicketScheduleController extends Controller
{
  public function index()
{
    $employees = Employee::with(['position', 'kelasAsuh'])
        ->where(function ($query) {
            $query->whereIn('role_id', [1, 4])
                  ->orWhereNotNull('position_id');
        })
        ->orderBy('fullname') // Urutkan berdasarkan nama (A-Z)
        ->get();

    $schedules = PicketSchedule::with('employee')->orderBy('picket_date')->get();

    return view('piketschedule.piket', compact('schedules', 'employees'));
    }

    // Form tambah jadwal
    public function create()
    {
        $employees = Employee::all();
        return view('picket.create', compact('employees'));
    }

    // Simpan jadwal baru
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id_employee',
            'picket_date' => 'required|date',
        ]);

        PicketSchedule::create($request->all());

        return redirect()->route('picket.index')->with('success', 'Jadwal piket berhasil ditambahkan.');
    }

    // Form edit jadwal
    public function edit($id)
    {
        $schedule = PicketSchedule::findOrFail($id);
        $employees = Employee::all();
        return view('picket.edit', compact('schedule', 'employees'));
    }

    // Update jadwal piket
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id_employee',
            'picket_date' => 'required|date',
        ]);

        $schedule = PicketSchedule::findOrFail($id);
        $schedule->update($request->all());

        return redirect()->route('picket.index')->with('success', 'Jadwal piket berhasil diperbarui.');
    }

    // Hapus jadwal piket
    public function destroy($id)
    {
        $schedule = PicketSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('picket.index')->with('success', 'Jadwal piket berhasil dihapus.');
    }
}
