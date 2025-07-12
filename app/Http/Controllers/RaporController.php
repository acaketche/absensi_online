<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Rapor;
use App\Models\AcademicYear;
use App\Models\Semester;
use App\Models\StudentSemester;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class RaporController extends Controller
{
    public function classes(Request $request)
    {
        $activeAcademicYear = AcademicYear::where('is_active', 1)->first();

        $query = Classes::with(['employee', 'academicYear'])
            ->withCount('students');

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        } elseif ($activeAcademicYear) {
            $query->where('academic_year_id', $activeAcademicYear->id);
        }

        if ($request->filled('class_level')) {
            $query->where('class_level', $request->class_level);
        }

        $classes = $query->get();
        $academicYears = AcademicYear::all();
        $classLevels = Classes::whereNotNull('class_level')
            ->select('class_level')
            ->distinct()
            ->orderBy('class_level')
            ->get();

        return view('rapor.rapor', compact('classes', 'academicYears', 'classLevels', 'activeAcademicYear'));
    }

   public function students($classId)
{
    $employee = Auth::guard('employee')->user();
    $class = Classes::with(['employee', 'academicYear'])->findOrFail($classId);
    $activeAcademicYear = AcademicYear::where('is_active', 1)->first();

    // 🔐 Batasi akses hanya untuk wali kelas yang sesuai
    if ($employee->role->role_name === 'Wali Kelas' && $class->id_employee !== $employee->id_employee) {
        abort(403, 'Anda tidak memiliki akses ke kelas ini.');
    }

    $students = Student::whereHas('studentSemesters', function ($query) use ($classId, $activeAcademicYear) {
        $query->where('class_id', $classId)
              ->where('academic_year_id', optional($activeAcademicYear)->id);
    })->with([
        'rapor',
        'studentSemesters' => function ($query) use ($classId, $activeAcademicYear) {
            $query->where('class_id', $classId)
                  ->where('academic_year_id', optional($activeAcademicYear)->id)
                  ->with('semester');
        }
    ])->get();

    $rapors = Rapor::whereIn('id_student', $students->pluck('id_student'))->get()->keyBy('id_student');

    return view('rapor.raporstudent', compact('class', 'students', 'rapors'));
}
   public function store(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $validator = Validator::make($request->all(), [
            'id_student'     => 'required|exists:students,id_student',
            'report_date'    => 'required|date',
            'report_file'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'description'    => 'nullable|string|max:500',
            'status_report'  => 'required|in:Belum Ada,Sudah Ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_modal', true);
        }

        DB::beginTransaction();
        try {
            $studentSemester = StudentSemester::where('student_id', $request->id_student)
                ->whereHas('academicYear', fn($q) => $q->where('is_active', 1))
                ->with('class', 'academicYear', 'semester')
                ->firstOrFail();

            $student = Student::findOrFail($request->id_student);

            $existingRapor = Rapor::where('id_student', $student->id_student)
                ->where('academic_year_id', $studentSemester->academic_year_id)
                ->where('semester_id', $studentSemester->semester_id)
                ->first();

            if ($existingRapor) {
                return redirect()->back()
                    ->with('error', 'Rapor untuk siswa ini pada tahun ajaran dan semester yang sama sudah ada.')
                    ->with('error_modal', true)
                    ->with('student_id', $student->id_student);
            }

            $filePath = null;
            if ($request->hasFile('report_file')) {
                $folderName = 'rapor/rapor ' . $studentSemester->class->class_name;
                $fileName = $student->id_student . '_' . $student->fullname . '.' . $request->file('report_file')->getClientOriginalExtension();
                $filePath = $request->file('report_file')->storeAs($folderName, $fileName, 'public');
            }

            $rapor = Rapor::create([
                'id_student'        => $student->id_student,
                'class_id'          => $studentSemester->class_id,
                'academic_year_id'  => $studentSemester->academic_year_id,
                'semester_id'       => $studentSemester->semester_id,
                'report_date'       => $request->report_date,
                'description'       => $request->description,
                'file_path'         => $filePath,
                'status_report'     => $request->status_report,
            ]);

            DB::commit();

            Log::info('Membuat rapor baru', [
                'program' => 'Rapor',
                'aktivitas' => 'Membuat rapor baru',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_student' => $student->id_student,
                    'nama_siswa' => $student->fullname,
                    'kelas' => $studentSemester->class->class_name,
                    'tahun_ajaran' => $studentSemester->academicYear->name,
                    'semester' => $studentSemester->semester->name
                ]
            ]);

            return redirect()->route('rapor.students', ['classId' => $studentSemester->class_id])
                ->with('success', 'Rapor berhasil diupload!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal membuat rapor', [
                'program' => 'Rapor',
                'aktivitas' => 'Membuat rapor baru',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan rapor: ' . $e->getMessage())
                ->with('error_modal', true);
        }
    }

    public function update(Request $request, $id)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id'      => 'required|exists:semesters,id',
            'report_date'      => 'required|date',
            'description'      => 'nullable|string|max:500',
            'report_file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status_report'    => 'required|in:Belum Ada,Sudah Ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $rapor = Rapor::findOrFail($id);
            $student = Student::findOrFail($rapor->id_student);

            $studentSemester = StudentSemester::where('student_id', $rapor->id_student)
                ->where('academic_year_id', $request->academic_year_id)
                ->where('semester_id', $request->semester_id)
                ->with('class')
                ->firstOrFail();

            $duplicate = Rapor::where('id_student', $rapor->id_student)
                ->where('academic_year_id', $request->academic_year_id)
                ->where('semester_id', $request->semester_id)
                ->where('id', '!=', $id)
                ->first();

            if ($duplicate) {
                return redirect()->back()
                    ->with('error', 'Rapor untuk tahun ajaran dan semester ini sudah ada.')
                    ->withInput();
            }

            if ($request->hasFile('report_file')) {
                if ($rapor->file_path) {
                    Storage::disk('public')->delete($rapor->file_path);
                }

                $folderName = 'rapor/rapor ' . $studentSemester->class->class_name;
                $fileName = $student->id_student . '_' . $student->fullname . '.' . $request->file('report_file')->getClientOriginalExtension();
                $filePath = $request->file('report_file')->storeAs($folderName, $fileName, 'public');
                $rapor->file_path = $filePath;
            }

            $rapor->academic_year_id = $request->academic_year_id;
            $rapor->semester_id      = $request->semester_id;
            $rapor->report_date      = $request->report_date;
            $rapor->description      = $request->description;
            $rapor->status_report    = $request->status_report;
            $rapor->save();

            DB::commit();

            Log::info('Memperbarui rapor', [
                'program' => 'Rapor',
                'aktivitas' => 'Memperbarui rapor',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => [
                    'id_rapor' => $rapor->id,
                    'id_student' => $student->id_student,
                    'nama_siswa' => $student->fullname,
                    'kelas' => $studentSemester->class->class_name
                ]
            ]);

            return redirect()->route('rapor.students', $rapor->class_id)
                ->with('success', 'Rapor berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal memperbarui rapor', [
                'program' => 'Rapor',
                'aktivitas' => 'Memperbarui rapor',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'id_rapor' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui rapor: ' . $e->getMessage());
        }
    }

    public function destroy($id, Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        DB::beginTransaction();
        try {
            $rapor = Rapor::findOrFail($id);
            $classId = $rapor->class_id;
            $student = Student::find($rapor->id_student);

            // Hapus file rapor jika ada
            if ($rapor->file_path) {
                Storage::disk('public')->delete($rapor->file_path);
            }

            $raporData = [
                'id_rapor' => $rapor->id,
                'id_student' => $rapor->id_student,
                'nama_siswa' => $student ? $student->fullname : 'Unknown',
                'file_path' => $rapor->file_path,
                'tahun_ajaran' => $rapor->academicYear->name ?? 'Unknown',
                'semester' => $rapor->semester->name ?? 'Unknown'
            ];

            $rapor->delete();

            DB::commit();

            Log::info('Menghapus rapor', [
                'program' => 'Rapor',
                'aktivitas' => 'Menghapus rapor',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'data' => $raporData
            ]);

            return redirect()->route('rapor.students', $classId)
                ->with('success', 'Rapor berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus rapor', [
                'program' => 'Rapor',
                'aktivitas' => 'Menghapus rapor',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'id_rapor' => $id
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus rapor: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $rapor = Rapor::with(['student', 'academicYear', 'semester', 'class'])->findOrFail($id);
        return view('rapor.show', compact('rapor'));
    }

    public function download($id)
    {
        $rapor = Rapor::findOrFail($id);

        if (!$rapor->file_path || !Storage::disk('public')->exists($rapor->file_path)) {
            return redirect()->back()->with('error', 'File rapor tidak ditemukan.');
        }

        $student = Student::findOrFail($rapor->id_student);
        $fileName = 'Rapor_' . $student->fullname . '_' . $rapor->academicYear->name . '_' . $rapor->semester->name . '.' . pathinfo($rapor->file_path, PATHINFO_EXTENSION);

        return response()->download(storage_path('app/public/' . $rapor->file_path), $fileName);
    }

public function uploadRaporMassalKelas(Request $request)
    {
        $employee = Auth::guard('employee')->user();
        $roleName = $employee->role->role_name ?? 'Tidak diketahui';

        $request->validate([
            'class_id'   => 'required|exists:classes,class_id',
            'rapor_zip'  => 'required|file|mimes:zip,application/zip,application/x-zip-compressed,multipart/x-zip|max:51200', // 50MB
        ]);

        DB::beginTransaction();
        try {
            $class = Classes::findOrFail($request->class_id);
            $uploadedFile = $request->file('rapor_zip');
            $filename = $uploadedFile->hashName();
            $uploadedFile->storeAs('temp', $filename, 'public');

            $zipPath = storage_path('app/public/temp/' . $filename);
            $extractTo = storage_path('app/public/temp-rapor');

            if (!file_exists($zipPath)) {
                throw new \Exception('File ZIP tidak ditemukan setelah upload.');
            }

            if (!is_dir($extractTo)) {
                mkdir($extractTo, 0777, true);
            }

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== true) {
                throw new \Exception('ZIP tidak bisa dibuka.');
            }

            $zip->extractTo($extractTo);
            $zip->close();

            $errors = [];
            $successCount = 0;
            $studentFiles = [];

            $files = \File::allFiles($extractTo);

            foreach ($files as $fileObj) {
                $file = $fileObj->getFilename();
                $path = $fileObj->getPathname();
                $filenameOnly = pathinfo($file, PATHINFO_FILENAME);
                $id_student = explode('_', $filenameOnly)[0] ?? null;

                if (!$id_student || !is_numeric($id_student)) {
                    $errors[] = "$file → Format nama tidak valid. Gunakan format: NIPD_Nama.pdf";
                    continue;
                }

                if (isset($studentFiles[$id_student])) {
                    $errors[] = "$file → Duplikat file ditemukan untuk ID $id_student. File sebelumnya: {$studentFiles[$id_student]}.";
                    continue;
                } else {
                    $studentFiles[$id_student] = $file;
                }

                $student = Student::with('studentSemester')->where('id_student', $id_student)->first();

                if (!$student || !$student->studentSemester) {
                    $errors[] = "$file → Data semester siswa dengan ID $id_student tidak ditemukan.";
                    continue;
                }

                $studentSemester = $student->studentSemester;

                if ($studentSemester->class_id !== $class->class_id) {
                    $errors[] = "$file → Siswa dengan ID $id_student tidak terdaftar di kelas ini.";
                    continue;
                }

                $class_id = $studentSemester->class_id;
                $academic_year_id = $studentSemester->academic_year_id;
                $semester_id = $studentSemester->semester_id;

                $existingRapor = Rapor::where('id_student', $id_student)
                    ->where('academic_year_id', $academic_year_id)
                    ->where('semester_id', $semester_id)
                    ->first();

                if ($existingRapor) {
                    $errors[] = "$file → Rapor sudah ada untuk $id_student di tahun ajaran dan semester ini.";
                    continue;
                }

                try {
                    $uploaded = new \Illuminate\Http\UploadedFile($path, $file, null, null, true);

                    $folderName = 'rapor/rapor ' . $class->class_name;
                    $storedPath = $uploaded->storeAs($folderName, $file, 'public');

                    Rapor::create([
                        'id_student'        => $id_student,
                        'class_id'          => $class_id,
                        'academic_year_id'  => $academic_year_id,
                        'semester_id'       => $semester_id,
                        'report_date'       => $request->report_date ?? now(),
                        'description'       => $request->description ?? null,
                        'file_path'         => $storedPath,
                        'status_report'     => 'Sudah Ada',
                    ]);

                    $successCount++;
                } catch (\Exception $e) {
                    $errors[] = "$file → Gagal disimpan: " . $e->getMessage();
                }
            }

            DB::commit();

            // Hapus file sementara
            Storage::disk('public')->delete('temp/' . $filename);
            \File::deleteDirectory($extractTo);

            $message = "Upload rapor massal berhasil. $successCount file berhasil diupload.";
            if (!empty($errors)) {
                $message .= '<br><ul>';
                foreach ($errors as $error) {
                    $message .= "<li>$error</li>";
                }
                $message .= '</ul>';
            }

            Log::info('Upload rapor massal', [
                'program' => 'Rapor',
                'aktivitas' => 'Upload rapor massal',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'kelas' => $class->class_name,
                'berhasil' => $successCount,
                'gagal' => count($errors),
                'total_file' => count($files)
            ]);

            return redirect()->route('rapor.students', ['classId' => $class->class_id])
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file sementara jika ada
            if (isset($filename)) {
                Storage::disk('public')->delete('temp/' . $filename);
            }
            if (isset($extractTo) && is_dir($extractTo)) {
                \File::deleteDirectory($extractTo);
            }

            Log::error('Gagal upload rapor massal', [
                'program' => 'Rapor',
                'aktivitas' => 'Upload rapor massal',
                'waktu' => now()->toDateTimeString(),
                'id_employee' => $employee->id_employee,
                'role' => $roleName,
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
                'class_id' => $request->class_id ?? null
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat upload rapor massal: ' . $e->getMessage());
        }

        }
}
