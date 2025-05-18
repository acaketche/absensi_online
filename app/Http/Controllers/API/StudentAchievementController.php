<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rapor;
use App\Models\StudentAchievement as ModelsStudentAchievement;
use Illuminate\Http\Request;

class StudentAchievementController extends Controller
{
    public function index(Request $request)
    {
        try {
            $acchievement = ModelsStudentAchievement::where('id_student', $request->user()->id_student)
                ->with(['student', 'semester', 'subject'])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'student_id' => $item->id_student,
                        'student_name' => $item->student->fullname ?? null,
                        'subject' => $item->subject->subject_name ?? null,
                        'score' => $item->score,
                        'semester' => $item->semester->semester_name ?? null,
                        'student_rank' => $item->student_rank,
                        'remark' => $item->remark,
                    ];
                });

            $rapor = Rapor::where('id_student', $request->user()->id_student)
                ->with(['student', 'semester'])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'student_id' => $item->id_student,
                        'student_name' => $item->student->fullname ?? null,
                        'semester' => $item->semester->semester_name ?? null,
                        'academic_year' => $item->academicYear->year_name ?? null,
                        'class' => $item->class->class_name ?? null,
                        'report_date' => $item->report_date,
                        'file_path' => $item->file_path,
                        'description' => $item->description,
                    ];
                });

            return response()->json([
                'message' => 'Get History Books Loan',
                'data' => [
                    'acchievement' => $acchievement,
                    'rapor' => $rapor
                ],
                'code' => 200,
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'code' => 400,
                'status' => 'error'
            ]);
        }
    }
}
