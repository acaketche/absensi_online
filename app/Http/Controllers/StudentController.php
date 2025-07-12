<?php

namespace App\Http\Controllers;

use App\Models\{Student, Classes, AcademicYear, Semester, StudentSemester};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Storage, Session, Log};
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
        $selectedSemester = $request->semester_id; // tidak default ke activeSemester
        $selectedClass = $request->class_id;

        // Kelas hanya berdasarkan tahun ajaran yang dipilih
        $classes = Classes::where('academic_year_id', $selectedYear)->get();

        // Ambil siswa berdasarkan studentSemesters
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
        ->get()
        // Sorting berdasarkan nama kelas (jika semester tersedia, filter spesifik semester; jika tidak, ambil kelas pertama dari tahun ajaran tersebut)
        ->sortBy(function ($student) use ($selectedYear, $selectedSemester) {
            $class = $student->studentSemesters
                ->where('academic_year_id', $selectedYear)
                ->when($selectedSemester, fn($q) => $q->where('semester_id', $selectedSemester))
                ->first()?->class?->class_name;

            return $class ?? ''; // fallback jika class_name tidak tersedia
        })->values();

        return view('students.index', compact(
            'students', 'academicYears', 'semesters', 'classes',
            'selectedYear', 'selectedSemester', 'selectedClass',
            'activeYear', 'activeSemester'
        ));
    }

   public function create()
{
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    $classes = Classes::where('academic_year_id', $activeAcademicYear?->id)->get();

    return view('students.create', [
        'classes' => $classes,
        'activeAcademicYear' => $activeAcademicYear,
        'activeSemester' => $activeSemester,
    ]);
}
   public function store(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

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
                'password' => 'required|string|min:6',
                'class_id' => 'required|exists:classes,class_id',
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
                'class_id' => $request->class_id,
                'academic_year_id' => $activeAcademicYear->id,
                'semester_id' => $activeSemester->id,
            ]);

            Log::info('Menambahkan siswa baru', [
                'program' => 'Siswa',
                'aktivitas' => 'Menambahkan siswa baru',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'student_id' => $student->id_student,
            ]);

            return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menambahkan siswa', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal menambahkan siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

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
    $student = Student::findOrFail($id);
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
    $activeSemester = Semester::where('is_active', 1)->first();

    // Fallback jika tidak ada data semester aktif
    $studentSemester = StudentSemester::with('class')
        ->where('student_id', $student->id_student)
        ->where('academic_year_id', $activeAcademicYear?->id)
        ->where('semester_id', $activeSemester?->id)
        ->first();

    if (!$studentSemester) {
        $studentSemester = StudentSemester::with('class')
            ->where('student_id', $student->id_student)
            ->orderByDesc('academic_year_id')
            ->orderByDesc('semester_id')
            ->first();
    }

$classes = Classes::where('academic_year_id', $activeAcademicYear?->id)
    ->orWhere('class_id', $studentSemester?->class_id)
    ->get();

    return view('students.edit', compact('student', 'classes', 'activeAcademicYear', 'activeSemester', 'studentSemester'));
}

   public function update(Request $request, $id)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

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
                'password' => 'nullable|string|min:6',
                'class_id' => 'required|exists:classes,class_id',
            ]);

            $classId = $request->class_id;
            $data = $request->except(['photo', 'qr_code', 'password', 'class_id']);

            if ($request->filled('password')) {
                $data['password'] = \Hash::make($request->password);
            }

            if ($request->hasFile('photo')) {
                if ($student->photo) {
                    \Storage::disk('public')->delete($student->photo);
                }
                $data['photo'] = $request->file('photo')->store('photo_siswa', 'public');
            }

            if ($request->hasFile('qr_code')) {
                if ($student->qr_code) {
                    \Storage::disk('public')->delete($student->qr_code);
                }
                $data['qr_code'] = $request->file('qr_code')->store('qrcode_siswa', 'public');
            }

            $student->update($data);

            $activeAcademicYear = AcademicYear::where('is_active', 1)->first();
            $activeSemester = Semester::where('is_active', 1)->first();

            if ($activeAcademicYear && $activeSemester) {
                $studentSemester = StudentSemester::firstOrNew([
                    'student_id' => $student->id_student,
                    'academic_year_id' => $activeAcademicYear->id,
                    'semester_id' => $activeSemester->id,
                ]);

                if ($studentSemester->class_id != $classId) {
                    $studentSemester->class_id = $classId;
                    $studentSemester->save();
                }
            }

            Log::info('Memperbarui data siswa', [
                'program' => 'Siswa',
                'aktivitas' => 'Memperbarui data siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'student_id' => $student->id_student,
            ]);

            return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui siswa', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal memperbarui siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'student_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

  public function destroy($id)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        try {
            $student = Student::findOrFail($id);

            StudentSemester::where('student_id', $student->id_student)->delete();

            if ($student->photo) Storage::disk('public')->delete($student->photo);
            if ($student->qr_code) Storage::disk('public')->delete($student->qr_code);

            $student->delete();

            Log::info('Menghapus siswa', [
                'program' => 'Siswa',
                'aktivitas' => 'Menghapus siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => request()->ip(),
                'student_id' => $id,
            ]);

            return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus siswa', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal menghapus siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => request()->ip(),
                'student_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
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
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

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

                    Log::warning('Import siswa dengan beberapa kesalahan', [
                        'program' => 'Siswa',
                        'aktivitas' => 'Import siswa dengan beberapa kesalahan',
                        'waktu' => now()->toDateTimeString(),
                        'id_employee' => auth('employee')->id(),
                        'role' => $roleName,
                        'ip' => $request->ip(),
                        'success_count' => $import->getImportedCount(),
                        'failed_rows' => $failedRows,
                    ]);

                    return redirect()->route('students.index')
                        ->with('success', "Berhasil mengimpor {$import->getImportedCount()} siswa.")
                        ->withErrors($errorBag, 'import');
                }

                Log::info('Berhasil import siswa', [
                    'program' => 'Siswa',
                    'aktivitas' => 'Import siswa',
                    'waktu' => now()->toDateTimeString(),
                    'id_employee' => auth('employee')->id(),
                    'role' => $roleName,
                    'ip' => $request->ip(),
                    'count' => $import->getImportedCount(),
                ]);

                return redirect()->route('students.index')
                    ->with('success', "Berhasil mengimpor {$import->getImportedCount()} siswa.");
            }

            return back()->with('error', 'Tidak ada file yang diunggah.');
        } catch (\Exception $e) {
            Log::error('Gagal import siswa', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal import siswa',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Kesalahan saat mengimpor: ' . $e->getMessage());
        }
    }

    public function showTemplate()
    {
        return view('excel.ExportTemplateSiswa');
    }

    public function downloadTemplateFilled()
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        Log::info('Mengunduh template siswa (berisi data)', [
            'program' => 'Siswa',
            'aktivitas' => 'Mengunduh template siswa berisi data',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => auth('employee')->id(),
            'role' => $roleName,
            'ip' => request()->ip(),
        ]);

        return Excel::download(new StudentTemplateExport('filled'), 'template_siswa_dari_data.xlsx');
    }

    public function downloadTemplateEmpty()
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        Log::info('Mengunduh template siswa (kosong)', [
            'program' => 'Siswa',
            'aktivitas' => 'Mengunduh template siswa kosong',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => auth('employee')->id(),
            'role' => $roleName,
            'ip' => request()->ip(),
        ]);

        return Excel::download(new StudentTemplateExport('empty'), 'template_siswa_kosong.xlsx');
    }

    public function uploadMediaZip(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate(['media_zip' => 'nullable|file|mimes:zip|max:20480']);

        $filename = $request->file('media_zip')->hashName();
        $request->file('media_zip')->storeAs('temp', $filename, 'public');
        $zipPath = Storage::disk('public')->path("temp/$filename");
        $extractTo = storage_path('app/public/temp-media');

        if (!file_exists($zipPath)) {
            Log::error('Gagal upload media ZIP - file tidak ditemukan', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal upload media ZIP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => 'File ZIP tidak ditemukan',
            ]);

            return back()->with('error', 'ZIP tidak ditemukan');
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($extractTo);
            $zip->close();
        } else {
            Storage::disk('public')->delete("temp/$filename");

            Log::error('Gagal membuka file ZIP', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal membuka file ZIP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => 'Gagal membuka file ZIP',
            ]);

            return back()->with('error', 'ZIP gagal dibuka');
        }

        $photos = $this->findFolderPath($extractTo, 'photos');
        $qrcodes = $this->findFolderPath($extractTo, 'qrcodes');

        if (!$photos || !$qrcodes) {
            Storage::disk('public')->delete("temp/$filename");
            \File::deleteDirectory($extractTo);

            Log::error('Folder photos/qrcodes tidak ditemukan dalam ZIP', [
                'program' => 'Siswa',
                'aktivitas' => 'Gagal upload media ZIP',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => auth('employee')->id(),
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => 'Folder photos/qrcodes tidak ditemukan',
            ]);

            return back()->with('error', 'Folder photos/qrcodes tidak ditemukan.');
        }

        $errors = [
            'photo' => $this->processMediaFiles($photos, 'photo', 'photo_siswa'),
            'qrcode' => $this->processMediaFiles($qrcodes, 'qrcode', 'qrcode_siswa')
        ];

        Storage::disk('public')->delete("temp/$filename");
        \File::deleteDirectory($extractTo);

        $successCount = count(array_filter($errors, function($error) {
            return empty($error);
        }));

        Log::info('Upload media ZIP berhasil', [
            'program' => 'Siswa',
            'aktivitas' => 'Upload media ZIP',
            'waktu' => now()->toDateTimeString(),
            'id_employee' => auth('employee')->id(),
            'role' => $roleName,
            'ip' => $request->ip(),
            'success_count' => $successCount,
            'errors' => array_filter($errors),
        ]);

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
