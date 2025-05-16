<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classes;
use App\Models\Semester;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function profile(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Profile retrieved successfully',
            'data' => [
                'student' => [
                    'id_student' => $request->user()->id_student,
                    'fullname' => $request->user()->fullname,
                    'parent_phonecell' => $request->user()->parent_phonecell,
                    'photo' => $request->user()->photo,
                    'birth_place' => $request->user()->birth_place,
                    'birth_date' => $request->user()->birth_date,
                    'gender'=> $request->user()->gender,
                    "academic_year" => AcademicYear::where('id', $request->user()->academic_year_id)->first()->year_name,
                    "semester" => Semester::where('id', $request->user()->semester_id)->first()->semester_name,
                    'student_class' => Classes::where('class_id', $request->user()->class_id)->first()->class_name,
                ]
            ]
        ]);
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'parent_phonecell' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'password' => 'nulable|string|min:8|confirmed',
        ]);
        $student = $request->user();
        $student->fullname = $request->fullname;
        $student->parent_phonecell = $request->parent_phonecell;
        $student->birth_place = $request->birth_place;
        $student->birth_date = $request->birth_date;
        if ($request->password) {
            $student->password = bcrypt($request->password);
        }
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/students'), $filename);
            $student->photo = 'images/students/' . $filename;
        }
        $student->save();
        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'student' => [
                    'id_student' => $student->id_student,
                    'fullname' => $student->fullname,
                    'parent_phonecell' => $student->parent_phonecell,
                    'photo' => $student->photo ? asset($student->photo) : null,
                    'birth_place' => $student->birth_place,
                    'birth_date' => $student->birth_date,
                ]
            ],
            'code' => 200
        ]);
    }
}
