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
    /**
     * Menampilkan daftar kelas
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
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

    /**
     * Menampilkan daftar siswa dalam kelas
     *
     * @param int $classId
     * @return \Illuminate\View\View
     */
    public function students($classId)
    {
        $class = Classes::with(['employee', 'academicYear'])->findOrFail($classId);

        // Load students with their rapor data
        $students = Student::where('class_id', $classId)
            ->with(['rapor', 'academicYear', 'semester'])
            ->get();

        return view('rapor.raporstudent', compact('class', 'students'));
    }

    /**
     * Menyimpan rapor baru dari modal upload
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'id_student'     => 'required|exists:students,id_student',
            'report_date'    => 'required|date',
            'report_file'    => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'description'    => 'nullable|string|max:500',
            'status_report'  => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_modal', true); // Flag untuk menampilkan kembali modal jika ada error
        }

        // Ambil data siswa
        $student = Student::with(['class', 'academicYear', 'semester'])->findOrFail($request->id_student);

        // Cek apakah sudah ada rapor untuk siswa, tahun ajaran, dan semester yang sama
        $existingRapor = Rapor::where('id_student', $student->id_student)
            ->where('academic_year_id', $student->academic_year_id)
            ->where('semester_id', $student->semester_id)
            ->first();

        if ($existingRapor) {
            return redirect()->back()
                ->with('error', 'Rapor untuk siswa ini pada tahun ajaran dan semester yang sama sudah ada.')
                ->with('error_modal', true)
                ->with('student_id', $student->id_student); // Untuk membuka kembali modal dengan siswa yang sama
        }

        // Upload file
        $fileName = null;
        if ($request->hasFile('report_file')) {
            $file = $request->file('report_file');
            $fileName = 'rapor_' . $student->nis . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('rapor', $fileName, 'public');
        }

        // Simpan data rapor
        Rapor::create([
            'id_student'        => $student->id_student,
            'class_id'          => $student->class_id,
            'academic_year_id'  => $student->academic_year_id,
            'semester_id'       => $student->semester_id,
            'report_date'       => $request->report_date,
            'description'       => $request->description,
            'file_path'         => 'rapor/' . $fileName,
            'status_report'     => $request->status_report,
        ]);

        return redirect()->route('rapor.students', ['classId' => $student->class_id])
                         ->with('success', 'Rapor berhasil diupload!');
    }

    /**
     * Menampilkan form untuk edit rapor
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $rapor = Rapor::with(['student', 'academicYear', 'semester', 'class'])->findOrFail($id);
        $academicYears = AcademicYear::all();
        $semesters = Semester::all();

        return view('rapor.edit', compact('rapor', 'academicYears', 'semesters'));
    }

    /**
     * Update rapor yang sudah ada
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'semester_id'      => 'required|exists:semesters,id',
            'report_date'      => 'required|date',
            'description'      => 'nullable|string|max:500',
            'report_file'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status_report'    => 'required|in:pending,approved,rejected',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rapor = Rapor::findOrFail($id);
        $student = Student::findOrFail($rapor->id_student);

        // Cek apakah sudah ada rapor lain untuk siswa, tahun ajaran, dan semester yang sama
        $existingRapor = Rapor::where('id_student', $rapor->id_student)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('semester_id', $request->semester_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRapor) {
            return redirect()->back()
                ->with('error', 'Rapor untuk tahun ajaran dan semester ini sudah ada.')
                ->withInput();
        }

        // Update file jika ada
        if ($request->hasFile('report_file')) {
            // Hapus file lama jika ada
            if ($rapor->file_path) {
                Storage::disk('public')->delete($rapor->file_path);
            }

            // Upload file baru
            $file = $request->file('report_file');
            $fileName = 'rapor_' . $student->nis . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('rapor', $fileName, 'public');
            $rapor->file_path = 'rapor/' . $fileName;
        }

        // Update data rapor
        $rapor->academic_year_id = $request->academic_year_id;
        $rapor->semester_id      = $request->semester_id;
        $rapor->report_date      = $request->report_date;
        $rapor->description      = $request->description;
        $rapor->status_report    = $request->status_report;
        $rapor->save();

        return redirect()->route('rapor.students', $rapor->class_id)
            ->with('success', 'Rapor berhasil diupdate.');
    }

    /**
     * Menghapus rapor
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Menampilkan detail rapor
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $rapor = Rapor::with(['student', 'academicYear', 'semester', 'class'])->findOrFail($id);
        return view('rapor.show', compact('rapor'));
    }

    /**
     * Download file rapor
     *
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
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
}
