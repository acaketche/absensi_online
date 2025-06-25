<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Models\Student;
use App\Models\Rapor;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RaporController extends Controller
{
    public function classes(Request $request)
    {
        $query = Classes::with(['employee', 'academicYear'])
            ->withCount('students');

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        $classes = $query->get();
        $academicYears = AcademicYear::all();

        return view('rapor.rapor', compact('classes', 'academicYears'));
    }

    public function students($classId)
    {
        $class = Classes::with(['employee', 'academicYear'])->findOrFail($classId);

        // Load students with their rapor data
        $students = Student::where('class_id', $classId)
            ->with(['rapor', 'academicYear', 'semester'])
            ->get();
        $rapors = Rapor::whereIn('id_student', $students->pluck('id_student'))->get()->keyBy('id_student');
        return view('rapor.raporstudent', compact('class', 'students','rapors'));
    }


   public function store(Request $request)
{
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

    $student = Student::with(['class', 'academicYear', 'semester'])->findOrFail($request->id_student);

    $existingRapor = Rapor::where('id_student', $student->id_student)
        ->where('academic_year_id', $student->academic_year_id)
        ->where('semester_id', $student->semester_id)
        ->first();

    if ($existingRapor) {
        return redirect()->back()
            ->with('error', 'Rapor untuk siswa ini pada tahun ajaran dan semester yang sama sudah ada.')
            ->with('error_modal', true)
            ->with('student_id', $student->id_student);
    }

    $filePath = null;
    if ($request->hasFile('report_file')) {
        $folderName = 'rapor/rapor ' . $student->class->class_name;
        $fileName = $student->id_student . '_' . $student->fullname . '.' . $request->file('report_file')->getClientOriginalExtension();
        $filePath = $request->file('report_file')->storeAs($folderName, $fileName, 'public');
    }

    Rapor::create([
        'id_student'        => $student->id_student,
        'class_id'          => $student->class_id,
        'academic_year_id'  => $student->academic_year_id,
        'semester_id'       => $student->semester_id,
        'report_date'       => $request->report_date,
        'description'       => $request->description,
        'file_path'         => $filePath,
        'status_report'     => $request->status_report,
    ]);

    return redirect()->route('rapor.students', ['classId' => $student->class_id])
        ->with('success', 'Rapor berhasil diupload!');
}

    public function edit($id)
    {
        $rapor = Rapor::with(['student', 'academicYear', 'semester', 'class'])->findOrFail($id);
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('rapor.edit', compact('rapor', 'academicYears', 'semesters'));
    }

  public function update(Request $request, $id)
{
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

    $rapor = Rapor::findOrFail($id);
    $student = Student::with('class')->findOrFail($rapor->id_student);

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

        $folderName = 'rapor/rapor ' . $student->class->class_name;
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

    return redirect()->route('rapor.students', $rapor->class_id)
        ->with('success', 'Rapor berhasil diupdate.');
}

    public function destroy($id)
    {
        $rapor = Rapor::findOrFail($id);
        $classId = $rapor->class_id;

        // Hapus file rapor jika ada
        if ($rapor->file_path) {
            Storage::disk('public')->delete($rapor->file_path);
        }

        $rapor->delete();

        return redirect()->route('rapor.students', $classId)
            ->with('success', 'Rapor berhasil dihapus.');
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
    $request->validate([
        'class_id' => 'required|exists:classes,class_id',
        'rapor_zip'  => 'required|file|mimes:zip|max:51200', // 50MB
    ]);

    $class = Classes::with('students')->findOrFail($request->class_id);
    $uploadedFile = $request->file('rapor_zip');
    $filename = $uploadedFile->hashName();
    $uploadedFile->storeAs('temp', $filename, 'public');

    $zipPath = storage_path('app/public/temp/' . $filename);
    $extractTo = storage_path('app/public/temp-rapor');

    if (!file_exists($zipPath)) {
        return back()->with('error', 'File ZIP tidak ditemukan setelah upload.');
    }

    if (!is_dir($extractTo)) {
        mkdir($extractTo, 0777, true);
    }

    $zip = new \ZipArchive;
    if ($zip->open($zipPath) === true) {
        $zip->extractTo($extractTo);
        $zip->close();
    } else {
        Storage::disk('public')->delete('temp/' . $filename);
        return back()->with('error', 'ZIP tidak bisa dibuka.');
    }

    $errors = [];
    $studentFiles = [];

    // Ambil semua file di dalam subfolder
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

        $student = Student::with(['academicYear', 'semester'])
            ->where('id_student', $id_student)
            ->where('class_id', $class->class_id)
            ->first();

        if (!$student) {
            $errors[] = "$file → Siswa dengan ID $id_student tidak ditemukan di kelas ini.";
            continue;
        }

        $existingRapor = Rapor::where('id_student', $id_student)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('semester_id', $student->semester_id)
            ->first();

        if ($existingRapor) {
            $errors[] = "$file → Rapor sudah ada untuk $id_student di tahun ajaran dan semester ini.";
            continue;
        }

        try {
            $uploaded = new \Illuminate\Http\UploadedFile($path, $file, null, null, true);

            // Simpan file ke storage/app/public/rapor/rapor {nama_kelas}
            $folderName = 'rapor/rapor ' . $class->class_name;
            $storedPath = $uploaded->storeAs($folderName, $file, 'public');

            Rapor::create([
                'id_student'        => $id_student,
                'class_id'          => $student->class_id,
                'academic_year_id'  => $student->academic_year_id,
                'semester_id'       => $student->semester_id,
                'report_date'       => $request->report_date ?? now(),
                'description'       => $request->description ?? null,
                'file_path'         => $storedPath,
                'status_report'     => 'Sudah Ada',
            ]);
        } catch (\Exception $e) {
            $errors[] = "$file → Gagal disimpan: " . $e->getMessage();
        }
    }

    // Bersihkan file sementara
    Storage::disk('public')->delete('temp/' . $filename);
    \File::deleteDirectory($extractTo);

    $message = 'Upload rapor massal berhasil.';
    if (!empty($errors)) {
        $message .= '<br><ul>';
        foreach ($errors as $error) {
            $message .= "<li>$error</li>";
        }
        $message .= '</ul>';
        return redirect()->route('rapor.students', ['classId' => $class->class_id])
            ->with('success', $message);
    }

    return redirect()->route('rapor.students', ['classId' => $class->class_id])
        ->with('success', $message);
}
}
