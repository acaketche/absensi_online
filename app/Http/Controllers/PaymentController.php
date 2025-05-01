<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Classes;
use App\Models\AcademicYear;
use App\Models\Semester;

class PaymentController extends Controller
{

public function bayar(Request $request){
    $id_student = $request->post("id_siswa");
    $amount = $request->post("amount");
    $id_spp = $request->post("id_spp");
    $academic_year_id = $request->post("academic_year_id");
    $semester_id = $request->post("semester_id");
    DB::statement ("INSERT INTO `payments`
SET
  `id_student` = '$id_student',
  `academic_year_id` = '$academic_year_id',
  `semester_id` = '$semester_id',
  `id_spp` = '$id_spp',
  `amount` = '$amount',
  `status` = 'lunas',
  `last_update` = NOW(),
  `notes` = ''
");


}

public function batalbayar(Request $request){
    $id_student = $request->post("id_siswa");
    $amount = $request->post("amount");
    $id_spp = $request->post("id_spp");
    $academic_year_id = $request->post("academic_year_id");
    $semester_id = $request->post("semester_id");
    DB::statement ("DELETE FROM `payments`
WHERE
  `id_student` = '$id_student' AND
  `academic_year_id` = '$academic_year_id' AND
  `semester_id` = '$semester_id' AND
  `id_spp` = '$id_spp'
");

}

    // Menampilkan form tambah pembayaran
    public function create(Request $request)
    {
        //dd($request);
        $academicYears = AcademicYear::all();
     $semesters = Semester::all();
     $classes = Classes::all();

     if ($request->has('simpan')) {
        // Handle the form submission
        $id = uniqid();
        DB::statement("INSERT INTO `spp`
        SET
        `id` = ?,
          `academic_year_id` = ?,
          `semester_id` = ?,
          `class_id` = ?,
          `amount` = ?,
          `created_at` = ?

        ", [
            $id, $request->post('academic_year_id'), $request->post('semester_id'),
             $request->post('class_id'), $request->post('nominal'), date('Y-m-d H:i:s')
        ]);
        return redirect('/payment/kelola/'.$id);
    }
        return view('spp.create', compact('academicYears', 'semesters','classes'));
    }


    public function listData()
    {
        $data = DB::select("SELECT a.*, b.`year_name`, c.`semester_name`, d.`class_name` FROM spp a
        JOIN academic_years b ON a.`academic_year_id` = b.`id`
        JOIN semesters c ON a.`semester_id` = c.`id`
        JOIN classes d ON a.`class_id` = d.`class_id`
        WHERE 1 ");

        return view('spp.list', compact('data'));
    }


    public function kelola($id)
    {
        $data = DB::select("SELECT a.*, b.`year_name`, c.`semester_name`, d.`class_name` FROM spp a
        JOIN academic_years b ON a.`academic_year_id` = b.`id`
        JOIN semesters c ON a.`semester_id` = c.`id`
        JOIN classes d ON a.`class_id` = d.`class_id`
        WHERE a.`id` = ?", [$id]);
        $data = $data[0];

        $siswa = DB::select("SELECT * FROM students a WHERE a.`class_id` = ?", [$data->class_id]);
        $bnt = DB::select("SELECT a.`id`, a.`amount`, a.`id_student` FROM payments a WHERE a.`id_spp` = ?",[$data->id]);
        $bayar = array();
        foreach($bnt as $a=>$b){
            $bayar[$b->id_student] = $b;
        }
        return view('spp.kelola', compact('data','siswa','bayar'));


    }

    // Menyimpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'id_student' => 'required',
            'academic_year_id' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
            'notes' => 'nullable|string'
        ]);

        Payment::create([
            'id_student' => $request->id_student,
            'academic_year_id' => $request->academic_year_id,
            'amount' => $request->amount,
            'status' => $request->status,
            'last_update' => now(),
            'notes' => $request->notes
        ]);

        return redirect()->route('payments.index')->with('success', 'Pembayaran berhasil ditambahkan');
    }

    // Menampilkan form edit pembayaran
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $students = Student::all();
        $academicYears = AcademicYear::all();
        return view('payments.edit', compact('payment', 'students', 'academicYears'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        DB::table('spp')
            ->where('id', $id)
            ->update([
                'amount' => $request->amount,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Data SPP berhasil diperbarui');
    }

    public function show($id)
{
    $payment = Payment::findOrFail($id);
    return view('payments.show', compact('payment'));
}

    // Menghapus pembayaran
    public function destroy($id)
    {
        // Cari data berdasarkan ID
        $payment = DB::table('spp')->where('id', $id)->first();

        // Jika data ditemukan
        if ($payment) {
            // Hapus data
            DB::table('spp')->where('id', $id)->delete();

            return redirect()->route('payment.listdata')->with('success', 'Data berhasil dihapus.');
        }

        // Jika tidak ditemukan
        return redirect()->route('payment.listdata')->with('error', 'Data tidak ditemukan.');
    }
}
