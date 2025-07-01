<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class StatusController extends Controller
{
    public function toggleAcademicYearStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $academicYear = AcademicYear::with('semesters')->findOrFail($id);

            // Nonaktifkan semua tahun ajaran lainnya
            AcademicYear::where('id', '!=', $id)->update(['is_active' => false]);

            // Update tahun ajaran yang dipilih
            $academicYear->update(['is_active' => $request->status]);

            // Update semester terkait
            if ($request->status) {
                // Aktifkan semester pertama jika tidak ada yang aktif
                if (!$academicYear->semesters->where('is_active', true)->count()) {
                    $firstSemester = $academicYear->semesters->first();
                    if ($firstSemester) {
                        $academicYear->semesters()->update(['is_active' => false]);
                        $firstSemester->update(['is_active' => true]);
                    }
                }
            } else {
                // Nonaktifkan semua semester jika tahun ajaran dinonaktifkan
                $academicYear->semesters()->update(['is_active' => false]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'academic_year' => $academicYear,
                'semesters' => $academicYear->semesters
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update academic year status'
            ], 500);
        }
    }

    public function toggleSemesterStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        DB::beginTransaction();
        try {
            $semester = Semester::with('academicYear')->findOrFail($id);
            $academicYear = $semester->academicYear;

            // Jika mengaktifkan semester, nonaktifkan semua semester lain di tahun yang sama
            if ($request->status) {
                $academicYear->semesters()
                    ->where('id', '!=', $id)
                    ->update(['is_active' => false]);

                // Pastikan tahun ajaran aktif
                if (!$academicYear->is_active) {
                    AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
                    $academicYear->update(['is_active' => true]);
                }
            }

            // Update semester yang dipilih
            $semester->update(['is_active' => $request->status]);

            DB::commit();

            return response()->json([
                'success' => true,
                'semester' => $semester,
                'academic_year' => $academicYear
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update semester status'
            ], 500);
        }
    }
}
