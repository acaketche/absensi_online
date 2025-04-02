<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;

class PaymentController extends Controller
{
    // Menampilkan daftar pembayaran
    public function index(Request $request)
    {
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
    $semesterId = $request->get('semester_id', $activeSemester->id ?? null);
    $classId = $request->get('class_id', null); // Tangkap filter kelas

    // Query siswa berdasarkan filter
    $payment = Payment::where('academic_year_id', $academicYearId)
                        ->when($semesterId, function ($query) use ($semesterId) {
                            return $query->where('semester_id', $semesterId);
                        })
                        ->when($classId, function ($query) use ($classId) {
                            return $query->where('class_id', $classId);
                        }) // Tambahkan filter class_id
                        ->get();

    $academicYears = AcademicYear::all();
    $semesters = Semester::all();
    $classes = Classes::all();

    return view('spp.spp', compact(
        'payment', 'classes', 'activeAcademicYear', 'activeSemester',
        'academicYears', 'semesters', 'academicYearId', 'semesterId', 'classId'
    ));
}

    // Menampilkan form tambah pembayaran
    public function create()
    {
        $students = Student::all();
        $academicYears = AcademicYear::all();
        return view('payments.create', compact('students', 'academicYears'));
    }

    // Menyimpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'id_student' => 'required',
            'academic_year_id' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
            'notes' => 'nullable|string'
        ]);

        Payment::create([
            'id_student' => $request->id_student,
            'academic_year_id' => $request->academic_year_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'last_update' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan');
    }

    // Menampilkan form edit pembayaran
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $students = Student::all();
        $academicYears = AcademicYear::all();
        return view('payments.edit', compact('payment', 'students', 'academicYears'));
    }

    // Memperbarui data pembayaran
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'notes' => 'nullable|string'
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => $request->status,
            'last_update' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->route('payments.index')->with('success', 'Status pembayaran berhasil diperbarui');
    }

    // Menghapus pembayaran
    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil dihapus');
    }
}
