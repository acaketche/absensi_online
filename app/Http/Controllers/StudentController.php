<?php

namespace App\Http\Controllers;

use App\Models\{Student, Classes, AcademicYear, Semester, StudentSemester};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Storage, Session};
use Illuminate\Support\MessageBag;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use App\Exports\StudentTemplateExport;
use ZipArchive;

class StudentController extends Controller
{
   public function index(Request $request)
{
    $academicYears = AcademicYear::all();
    $semesters = Semester::all();

    $activeYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $selectedYear = $request->academic_year_id ?? $activeYear?->id;
    $selectedSemester = $request->semester_id ?? $activeSemester?->id;
    $selectedClass = $request->class_id;

    $classes = Classes::where('academic_year_id', $selectedYear)->get();
    // Query siswa berdasarkan studentSemester
    $students = Student::whereHas('studentSemesters', function ($q) use ($selectedYear, $selectedSemester, $selectedClass) {
    $q->where('academic_year_id', $selectedYear);

    if ($selectedSemester) {
        $q->where('semester_id', $selectedSemester);
    }

    if ($selectedClass) {
        $q->where('class_id', $selectedClass);
    }
})
->with(['studentSemesters.class'])
->orderBy('fullname')
->get()
->sortBy(function ($student) use ($selectedYear, $selectedSemester) {
    return $student->studentSemesters
        ->where('academic_year_id', $selectedYear)
        ->when($selectedSemester, function ($q) use ($selectedSemester) {
            return $q->where('semester_id', $selectedSemester);
        })
        ->first()?->class?->class_name ?? '';
})->values(); //

    return view('students.index', compact(
        'students', 'academicYears', 'semesters', 'classes',
        'selectedYear', 'selectedSemester', 'activeYear', 'activeSemester'
    ));
}

    public function create()
    {
        return view('students.create', [
            'classes' => Classes::all(),
            'activeAcademicYear' => AcademicYear::where('is_active', 1)->first(),
            'activeSemester' => Semester::where('is_active', 1)->first(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
            $activeSemester = Semester::where('is_active', 1)->first();

            $request->validate([
                'id_student' => 'required|numeric|unique:students,id_student',
                'fullname' => 'required|string|max:100',
                'parent_phonecell' => 'required|string|max:15',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'qr_code' => 'nullable|image|mimes:png|max:1024',
                'birth_place' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'gender' => 'required|in:L,P',
                'password' => 'required|string|min:6'
            ]);

            $photoPath = $request->hasFile('photo') ? $request->file('photo')->store('photo_siswa', 'public') : null;
            $qrPath = $request->hasFile('qr_code') ? $request->file('qr_code')->store('qrcode_siswa', 'public') : null;

            $student = Student::create([
                'id_student' => $request->id_student,
                'fullname' => $request->fullname,
                'password' => Hash::make($request->password),
                'birth_place' => $request->birth_place,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'parent_phonecell' => $request->parent_phonecell,
                'photo' => $photoPath,
                'qr_code' => $qrPath,
            ]);

            StudentSemester::create([
                'student_id' => $student->id_student,
                'class_id' => $student->class_id,
                'academic_year_id' => $activeAcademicYear->id,
                'semester_id' => $activeSemester->id,
            ]);

            return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        $semesters = StudentSemester::with(['academicYear', 'semester', 'class'])
            ->where('student_id', $student->id_student)
            ->orderByDesc('academic_year_id')
            ->orderByDesc('semester_id')
            ->get();

        return view('students.detail', compact('student', 'semesters'));
    }

    public function edit($id)
    {

        return view('students.edit', [
            'student' => Student::findOrFail($id),
            'classes' => Classes::all(),
            'activeAcademicYear' => AcademicYear::where('is_active', 1)->first(),
            'activeSemester' => Semester::where('is_active', 1)->first(),
        ]);
    }

   public function update(Request $request, $id)
{
    try {
        $student = Student::findOrFail($id);

        $request->validate([
            'id_student' => 'required|numeric|unique:students,id_student,' . $id . ',id_student',
            'fullname' => 'required|string|max:100',
            'parent_phonecell' => 'required|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_code' => 'nullable|image|mimes:png|max:1024',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'password' => 'nullable|string|min:6'
        ]);

        // Debug: Check incoming class_id
        \Log::info('Updating student class', [
            'student_id' => $id,
            'current_class' => $student->class_id,
            'new_class' => $request->class_id
        ]);

        $data = $request->except(['photo', 'qr_code', 'password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $data['photo'] = $request->file('photo')->store('photo_siswa', 'public');
        }

        if ($request->hasFile('qr_code')) {
            if ($student->qr_code) {
                Storage::disk('public')->delete($student->qr_code);
            }
            $data['qr_code'] = $request->file('qr_code')->store('qrcode_siswa', 'public');
        }

        // Update the student record
        $student->update($data);

        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
        $activeSemester = Semester::where('is_active', 1)->first();

        if ($activeAcademicYear && $activeSemester) {
            // Find existing record or create new one
            $studentSemester = StudentSemester::firstOrNew([
                'student_id' => $student->id_student,
                'academic_year_id' => $activeAcademicYear->id,
                'semester_id' => $activeSemester->id,
            ]);

            // Update class_id if it's different
            if ($studentSemester->class_id != $student->class_id) {
                $studentSemester->class_id = $student->class_id;
                $studentSemester->save();

                \Log::info('Updated student semester record', [
                    'student_id' => $student->id_student,
                    'new_class_id' => $student->class_id
                ]);
            }
        }

        return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
    } catch (\Exception $e) {
        \Log::error('Error updating student: ' . $e->getMessage());
        return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
    }
}
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        if ($student->photo) Storage::disk('public')->delete($student->photo);
        if ($student->qr_code) Storage::disk('public')->delete($student->qr_code);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }

    public function getStudentData($id)
    {
        return response()->json(Student::findOrFail($id));
    }

    public function search(Request $request)
    {
       $student = Student::where('id_student', $request->query('nis'))->with(['studentSemesters.class'])->first();
        return $student
            ? response()->json(['success' => true, 'student' => ['full_name' => $student->fullname,'class_name' => $student->studentSemesters->first()?->class?->class_name ?? 'Tidak Ada Kelas']])
            : response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan']);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'nullable|file|mimes:xlsx,xls|max:2048']);

        try {
            if ($request->hasFile('file')) {
                $import = new StudentImport();
                Excel::import($import, $request->file('file'));

                $failedRows = $import->getFailedRows();

                if (!empty($failedRows)) {
                    $errorBag = new MessageBag();
                    foreach ($failedRows as $row) {
                        $message = "Baris {$row['row']}: {$row['message']}";
                        $errorBag->add('import_error', $message);
                    }

                    return redirect()->route('students.index')
                        ->with('success', "Berhasil mengimpor {$import->getImportedCount()} siswa.")
                        ->withErrors($errorBag, 'import');
                }

                return redirect()->route('students.index')
                    ->with('success', "Berhasil mengimpor {$import->getImportedCount()} siswa.");
            }

            return back()->with('error', 'Tidak ada file yang diunggah.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }

    public function showTemplate()
    {
        return view('excel.ExportTemplateSiswa');
    }

   public function downloadTemplateFilled()
{
    return Excel::download(new StudentTemplateExport('filled'), 'template_siswa_dari_data.xlsx');
}

public function downloadTemplateEmpty()
{
    return Excel::download(new StudentTemplateExport('empty'), 'template_siswa_kosong.xlsx');
}

    public function uploadMediaZip(Request $request)
    {
        $request->validate(['media_zip' => 'nullable|file|mimes:zip|max:20480']);

        $filename = $request->file('media_zip')->hashName();
        $request->file('media_zip')->storeAs('temp', $filename, 'public');
        $zipPath = Storage::disk('public')->path("temp/$filename");
        $extractTo = storage_path('app/public/temp-media');

        if (!file_exists($zipPath)) return back()->with('error', 'ZIP tidak ditemukan');

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($extractTo);
            $zip->close();
        } else {
            Storage::disk('public')->delete("temp/$filename");
            return back()->with('error', 'ZIP gagal dibuka');
        }

        $photos = $this->findFolderPath($extractTo, 'photos');
        $qrcodes = $this->findFolderPath($extractTo, 'qrcodes');

        if (!$photos || !$qrcodes) {
            Storage::disk('public')->delete("temp/$filename");
            \File::deleteDirectory($extractTo);
            return back()->with('error', 'Folder photos/qrcodes tidak ditemukan.');
        }

        $errors = [
            'photo' => $this->processMediaFiles($photos, 'photo', 'photo_siswa'),
            'qrcode' => $this->processMediaFiles($qrcodes, 'qrcode', 'qrcode_siswa')
        ];

        Storage::disk('public')->delete("temp/$filename");
        \File::deleteDirectory($extractTo);

        return redirect()->back()->with([
            'success' => 'Upload berhasil.',
            'upload_errors' => array_filter($errors)
        ]);
    }

    private function processMediaFiles($folder, $column, $storageFolder)
    {
        $errors = [];
        foreach (scandir($folder) as $file) {
            if (in_array($file, ['.', '..'])) continue;
            $id_student = explode('_', pathinfo($file, PATHINFO_FILENAME))[0] ?? null;

            if (!$id_student || !is_numeric($id_student)) {
                $errors[] = "$file → Format nama tidak valid.";
                continue;
            }

            $student = Student::where('id_student', $id_student)->first();
            if (!$student) {
                $errors[] = "$file → ID '$id_student' tidak ditemukan.";
                continue;
            }

            try {
                $path = (new \Illuminate\Http\UploadedFile("$folder/$file", $file, null, null, true))
                    ->storeAs($storageFolder, $file, 'public');

                $student->$column = $path;
                $student->save();
            } catch (\Exception $e) {
                $errors[] = "$file → Gagal disimpan: " . $e->getMessage();
            }
        }
        return $errors;
    }

  private function findFolderPath($basePath, $target)
{
    foreach (scandir($basePath) as $item) {
        if (in_array($item, ['.', '..'])) continue;

        $fullPath = "$basePath/$item";

        if (is_dir($fullPath)) {
            if (basename($fullPath) === $target) return $fullPath;

            // Cek dalam folder lagi (rekursif)
            $nested = $this->findFolderPath($fullPath, $target);
            if ($nested) return $nested;
        }
    }
    return null;
}
}
