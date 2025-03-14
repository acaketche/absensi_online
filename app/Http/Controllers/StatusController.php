<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Semester;

class StatusController extends Controller
{
    public function toggleAcademicYearStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        // Nonaktifkan semua tahun ajaran terlebih dahulu
        AcademicYear::query()->update(['is_active' => 0]);

        // Aktifkan tahun ajaran yang dipilih
        $academicYear = AcademicYear::find($id);
        if ($academicYear) {
            $academicYear->update(['is_active' => $request->status]);

            // Jika Academic Year diaktifkan, pastikan setidaknya satu semester juga aktif
            if ($request->status == 1) {
                Semester::where('academic_year_id', $id)->update(['is_active' => 1]);
            } else {
                Semester::where('academic_year_id', $id)->update(['is_active' => 0]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Academic Year not found'], 404);
    }

    public function toggleSemesterStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:0,1',
            'id' => 'required|integer',
        ]);

        // Nonaktifkan semua semester dalam tahun akademik yang sama
        Semester::where('academic_year_id', $request->id)->update(['is_active' => 0]);

        // Aktifkan semester yang dipilih
        $semester = Semester::find($id);
        if ($semester) {
            $semester->update(['is_active' => $request->status]);

            // Jika semester diaktifkan, aktifkan academic year terkait
            if ($request->status == 1) {
                AcademicYear::where('id', $request->id)->update(['is_active' => 1]);
            }

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Semester not found'], 404);
    }
}
