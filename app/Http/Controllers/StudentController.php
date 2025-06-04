<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Import\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentTemplateExport;

class StudentController extends Controller
{
    public function index(Request $request)
    {

        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        $academicYearId = $request->get('academic_year_id', $activeAcademicYear->id ?? null);
        $semesterId = $request->get('semester_id', $activeSemester->id ?? null);
        $classId = $request->get('class_id', null);

        $students = Student::where('academic_year_id', $academicYearId)
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->get();

        $academicYears = AcademicYear::all();
        $semesters = Semester::all();
        $classes = Classes::all();

        return view('students.index', compact(
            'students', 'classes', 'activeAcademicYear', 'activeSemester',
            'academicYears', 'semesters', 'academicYearId', 'semesterId', 'classId'
        ));
    }

    public function create()
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();
        $classes = Classes::all();

        return view('students.create', compact('classes', 'activeAcademicYear', 'activeSemester'));
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);
        return view('students.detail', compact('student'));
    }

    public function store(Request $request)
    {
        try {
            $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
            $activeSemester = Semester::where('is_active', 1)->first();

            if (!$activeAcademicYear || !$activeSemester) {
                return redirect()->back()->with('error', 'Tahun Ajaran atau Semester aktif tidak ditemukan.');
            }

            $request->validate([
                'id_student' => 'required|numeric|unique:students,id_student',
                'fullname' => 'required|string|max:100',
                'class_id' => 'required|string|max:50',
                'parent_phonecell' => 'required|string|max:15',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'qr_code' => 'nullable|image|mimes:png|max:1024',
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'gender' => 'required|in:L,P',
                'password' => 'required|string|min:6'
            ]);

           $photoPath = null;
            $qrPath = null;

        if ($request->hasFile('photo')) {
            // Almacenar directamente en storage/app/public sin 'public/' al inicio
            $photoPath = $request->file('photo')->store('photo_siswa', 'public');
        }

        if ($request->hasFile('qr_code')) {
            // Almacenar directamente en storage/app/public sin 'public/' al inicio
            $qrPath = $request->file('qr_code')->store('qrcode_siswa', 'public');
        }

            Student::create([
                'id_student' => $request->id_student,
                'fullname' => $request->fullname,
                'password' => Hash::make($request->password),
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'parent_phonecell' => $request->parent_phonecell,
                'class_id' => $request->class_id,
                'academic_year_id' => $activeAcademicYear->id,
                'semester_id' => $activeSemester->id,
                'photo' => $photoPath,
                'qr_code' => $qrPath,
            ]);

            return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
{
    $student = Student::findOrFail($id);
    $classes = Classes::all();
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    return view('students.edit', compact('student', 'classes', 'activeAcademicYear', 'activeSemester'));
}


    public function update(Request $request, $id)
    {
        try {
            $student = Student::findOrFail($id);

            $request->validate([
                'id_student' => 'required|numeric|unique:students,id_student,' . $id . ',id_student',
                'fullname' => 'required|string|max:100',
                'class_id' => 'required|string|max:50',
                'parent_phonecell' => 'required|string|max:15',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'gender' => 'required|in:L,P',
                'password' => 'nullable|string|min:6'
            ]);

            $data = $request->except(['photo', 'password']);

            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            if ($request->hasFile('photo')) {
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }
                $data['photo'] = $request->file('photo')->store('photo_siswa', 'public');
            }

            $student->update($data);

            return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);

        }

        if ($student->qr_code) {
            Storage::disk('public')->delete($student->qr_code);

        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function getStudentData($id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function search(Request $request)
    {
        $nis = $request->query('nis');

        $student = Student::where('id_student', $nis)->with('class')->first();

        if ($student) {
            return response()->json([
                'success' => true,
                'student' => [
                    'full_name' => $student->fullname,
                    'class_name' => $student->class->class_name ?? 'Tidak Ada Kelas'
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
        }
    }
    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls'
    ]);

    try {
        Excel::import(new StudentImport, $request->file('file'));

        return redirect()->route('students.index')->with('success', 'Import siswa berhasil.');
    } catch (\Exception $e) {
        return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
    }
}
public function showTemplate()
{
    return view('excel.ExportTemplateSiswa'); // Halaman petunjuk
}

public function downloadTemplate()
{
    return Excel::download(new StudentTemplateExport(), 'student_import_template.xlsx');
}
}

