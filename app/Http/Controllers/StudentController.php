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
        'photos.*' => 'nullable|file|image|mimes:jpeg,png,jpg|max:2048',
        'qr_codes.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
    ], [
        'photos.*.image' => 'Setiap file foto harus berupa gambar.',
        'photos.*.mimes' => 'Format foto harus jpg, jpeg, atau png.',
        'photos.*.max' => 'Ukuran masing-masing foto maksimal 2MB.',
        'qr_codes.*.mimes' => 'Format QR Code harus jpg, jpeg, png, atau pdf.',
        'qr_codes.*.max' => 'Ukuran masing-masing QR Code maksimal 2MB.',
    ]);

    // ✅ Jika semua input kosong, kembalikan dengan pesan error
    if (
        !$request->hasFile('file') &&
        !$request->hasFile('photos') &&
        !$request->hasFile('qr_codes')
    ) {
        return back()->with('error', 'Silakan unggah file Excel, foto, atau QR code terlebih dahulu.');
    }

    try {
        $photoMap = [];
        $qrCodeMap = [];

        // ✅ Upload foto siswa dengan nama sesuai id_student
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $id = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $photo->getClientOriginalExtension();
                $filename = $id . '.' . $extension;
                $path = $photo->storeAs('photo_siswa', $filename, 'public');
                $photoMap[$id] = $path;
            }
        }

        // ✅ Upload QR code siswa dengan nama sesuai id_student
        if ($request->hasFile('qr_codes')) {
            foreach ($request->file('qr_codes') as $qr) {
                $id = pathinfo($qr->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $qr->getClientOriginalExtension();
                $filename = $id . '.' . $extension;
                $path = $qr->storeAs('qrcode_siswa', $filename, 'public');
                $qrCodeMap[$id] = $path;
            }
        }

        // Simpan session (jika dibutuhkan oleh StudentImport)
        session([
            'photo_map' => $photoMap,
            'qrcode_map' => $qrCodeMap,
        ]);

        $messages = [];

        // ✅ Jika file Excel diupload, jalankan proses import
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

        // ✅ Update foto dan QR code langsung ke database
        if (!empty($photoMap)) {
            foreach ($photoMap as $id => $path) {
                \DB::table('students')->where('id_student', $id)->update([
                    'photo' => $path,
                ]);
            }
            $messages['photo_success'] = 'Foto siswa berhasil diunggah dan disimpan.';
        }

        if (!empty($qrCodeMap)) {
            foreach ($qrCodeMap as $id => $path) {
                \DB::table('students')->where('id_student', $id)->update([
                    'qrcode' => $path,
                ]);
            }
            $messages['qr_success'] = 'QR Code siswa berhasil diunggah dan disimpan.';
        }

        // Hapus session setelah selesai
        session()->forget(['photo_map', 'qrcode_map']);

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
}
