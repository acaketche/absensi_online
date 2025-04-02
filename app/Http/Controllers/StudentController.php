<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
{
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
    $semesterId = $request->get('semester_id', $activeSemester->id ?? null);
    $classId = $request->get('class_id', null); // Tangkap filter kelas

    // Query siswa berdasarkan filter
    $students = Student::where('academic_year_id', $academicYearId)
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

    return view('students.index', compact(
        'students', 'classes', 'activeAcademicYear', 'activeSemester',
        'academicYears', 'semesters', 'academicYearId', 'semesterId', 'classId'
    ));
}

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.detail', compact('student'));
    }

    public function store(Request $request)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        if (!$activeAcademicYear || !$activeSemester) {
            return redirect()->back()->with('error', 'Tahun Ajaran atau Semester aktif tidak ditemukan.');
        }

        $request->validate([
            'id_student' => 'required|string|max:20|unique:students,id_student',
            'fullname' => 'required|string|max:100',
            'class_id' => 'required|string|max:50',
            'parent_phonecell' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'password' => 'required|string|min:6'
        ]);

        try {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            Student::create([
                'id_student' => $request->id_student,
                'fullname' => $request->fullname,
                'password' => bcrypt($request->password),
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date->format('Y-m-d'),
                'gender' => $request->gender,
                'parent_phonecell' => $request->parent_phonecell,
                'class_id' => $request->class_id,
                'academic_year_id' => $activeAcademicYear->id,
                'semester_id' => $activeSemester->id,
                'photo' => $photoPath,
            ]);

            return redirect()->route('students.index')->with('success', 'Student added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_student' => 'required|string|max:20|unique:students,id_student,' . $id . ',id_student',
            'fullname' => 'required|string|max:100',
            'class_id' => 'required|string|max:50',
            'parent_phonecell' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'password' => 'nullable|string|min:6'
        ]);

        $student = Student::findOrFail($id);
        $data = $request->except(['photo', 'password']);

        // Jika password diisi, enkripsi dan update
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }

            // Simpan foto baru
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $student->update($data);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        if ($student->photo) {
            Storage::delete('public/photos/' . $student->photo);
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
    public function search(Request $request)
    {
        $nis = $request->query('nis'); // Ambil input NIS dari request

        $student = Student::where('id_student', $nis)
            ->with('class') // Ambil data kelas dari relasi
            ->first();

        if ($student) {
            return response()->json([
                'success' => true,
                'student' => [
                    'full_name' => $student->fullname,
                    'class_name' => $student->class->class_name ?? 'Tidak Ada Kelas' // Pastikan relasi tidak null
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
        }
    }

}
