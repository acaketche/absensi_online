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
use ZipArchive;

class StudentController extends Controller
{
   public function index(Request $request)
{
    // Query untuk mengambil data siswa dengan pengurutan
    $students = Student::query()
        ->when($request->academic_year_id, function($query) use ($request) {
            $query->where('academic_year_id', $request->academic_year_id);
        })
        ->when($request->semester_id, function($query) use ($request) {
            $query->where('semester_id', $request->semester_id);
        })
        ->when($request->class_id, function($query) use ($request) {
            $query->where('class_id', $request->class_id);
        })
        ->with('class') // Eager load relasi kelas
        ->orderBy('class_id') // Urutkan berdasarkan kelas terlebih dahulu
        ->orderBy('fullname') // Kemudian urutkan berdasarkan nama siswa
        ->get();

    // Data lainnya yang diperlukan untuk filter
    $academicYears = AcademicYear::all();
    $semesters = Semester::all();
    $classes = Classes::all();

    return view('students.index', compact('students', 'academicYears', 'semesters', 'classes'));
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
        'file' => 'nullable|file|mimes:xlsx,xls|max:2048',
    ]);

    try {
        $messages = [];

        // Jalankan proses import jika file Excel diupload
        if ($request->hasFile('file')) {
            $import = new StudentImport();
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getImportedCount();
            $failedRows = $import->getFailedRows();

            if ($importedCount > 0) {
                $messages['success'] = "Berhasil mengimport {$importedCount} data siswa.";
            }

            if (count($failedRows) > 0) {
                $messages['errors'] = array_map(function ($row) {
                    return "Baris {$row['row']}: {$row['message']}";
                }, $failedRows);
            }
        }

        return redirect()->route('students.index')->with($messages);

    } catch (\Exception $e) {
        \Log::error('Import Error: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat mengimpor: ' . $e->getMessage());
    }
}

protected function formatFailures($failures)
{
    $formatted = [];
    foreach ($failures as $failure) {
        $formatted[] = [
            'row' => $failure->row(),
            'errors' => $failure->errors(),
            'values' => $failure->values()
        ];
    }
    return $formatted;
}
    public function showTemplate()
    {
        return view('excel.ExportTemplateSiswa'); // Halaman petunjuk
    }

    public function downloadTemplate()
    {
        return Excel::download(new StudentTemplateExport(), 'student_import_template.xlsx');
    }
 public function uploadMediaZip(Request $request)
{
    $request->validate([
        'media_zip' => 'nullable|file|mimes:zip|max:20480',
    ]);

    // Pastikan folder temp ada
    if (!Storage::disk('public')->exists('temp')) {
        Storage::disk('public')->makeDirectory('temp');
    }

    // Simpan file ZIP ke public/temp
    $uploadedFile = $request->file('media_zip');
    $filename = $uploadedFile->hashName();
    $uploadedFile->storeAs('temp', $filename, 'public');

    $realZipPath = storage_path('app/public/temp/' . $filename);
    $extractTo = storage_path('app/public/temp-media');

    if (!file_exists($realZipPath)) {
        return back()->with('error', 'File ZIP tidak ditemukan: ' . $realZipPath);
    }

    if (!is_dir($extractTo)) {
        mkdir($extractTo, 0777, true);
    }

    // Ekstrak ZIP
    $zip = new \ZipArchive;
    $openResult = $zip->open($realZipPath);
    if ($openResult === true) {
        $zip->extractTo($extractTo);
        $zip->close();
    } else {
        Storage::disk('public')->delete('temp/' . $filename);
        return back()->with('error', 'ZIP gagal dibuka (Kode error: ' . $openResult . ').');
    }

    // Cari folder photos dan qrcodes
    $photosPath = $this->findFolderPath($extractTo, 'photos');
    $qrcodesPath = $this->findFolderPath($extractTo, 'qrcodes');

    if (!$photosPath || !$qrcodesPath) {
        Storage::disk('public')->delete('temp/' . $filename);
        \File::deleteDirectory($extractTo);
        return back()->with('error', 'Folder photos atau qrcodes tidak ditemukan.');
    }

    $errors = [];
    $errors['photo'] = $this->processMediaFiles($photosPath, 'photo', 'photo_siswa');
    $errors['qrcode'] = $this->processMediaFiles($qrcodesPath, 'qrcode', 'qrcode_siswa');

    // Hapus file dan folder temp
    Storage::disk('public')->delete('temp/' . $filename);
    \File::deleteDirectory($extractTo);

    $message = 'Upload berhasil.';
    if (!empty($errors['photo']) || !empty($errors['qrcode'])) {
        $message .= ' Tapi ada beberapa file gagal diproses.';
    }

    return redirect()->back()->with([
        'success' => $message,
        'upload_errors' => array_filter($errors),
    ]);
}

private function processMediaFiles($folder, $column, $storageFolder)
{
    $errors = [];

    if (!is_dir($folder)) return ["Folder $folder tidak ditemukan di dalam ZIP."];

    foreach (scandir($folder) as $file) {
        if (in_array($file, ['.', '..'])) continue;

        $filename = pathinfo($file, PATHINFO_FILENAME);
        $id_student = explode('_', $filename)[0] ?? null;

        if (!$id_student || !is_numeric($id_student)) {
            $errors[] = "$file → Format nama tidak valid. Gunakan format: [id_student]_[nama].ext";
            continue;
        }

        $student = Student::where('id_student', $id_student)->first();
        if (!$student) {
            $errors[] = "$file → ID '$id_student' tidak ditemukan di database.";
            continue;
        }

        // Simpan ke storage Laravel dengan nama asli
        try {
            $fullPath = $folder . '/' . $file;

            // Buat instance UploadedFile agar bisa pakai store()
            $uploaded = new \Illuminate\Http\UploadedFile(
                $fullPath, $file, null, null, true // test mode
            );

            $storedPath = $uploaded->storeAs($storageFolder, $file, 'public');

            // Update path ke kolom yg sesuai
            $student->$column = $storedPath;
            $student->save();
        } catch (\Exception $e) {
            $errors[] = "$file → Gagal disimpan: " . $e->getMessage();
        }
    }

    return $errors;
}
private function findFolderPath($basePath, $targetFolder)
{
    foreach (scandir($basePath) as $item) {
        if (in_array($item, ['.', '..'])) continue;

        $fullPath = $basePath . '/' . $item;
        if (is_dir($fullPath)) {
            if (basename($fullPath) === $targetFolder) {
                return $fullPath;
            }

            // Cek dalam subfolder satu tingkat
            $nested = $this->findFolderPath($fullPath, $targetFolder);
            if ($nested) return $nested;
        }
    }
    return null;
}
}
