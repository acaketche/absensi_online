<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // DB::table('employees')->insert([
        //     'id_employee'   => 'EMP001',
        //     'fullname'      => 'Admin Utama',
        //     'birth_place'   => 'Jakarta',
        //     'birth_date'    => '1990-01-01',
        //     'gender'        => 'L',
        //     'phone_number'  => '081234567890',
        //     'email'         => 'fikryramadhan@gmail.com',
        //     'role_id'       => 2,
        //     'password'      => Hash::make('admin123'), // Jangan lupa ganti password ini nanti
        //     'photo'         => null,
        //     'qr_code'       => Str::random(20),
        //     'created_at'    => now(),
        //     'updated_at'    => now(),
        //     'position_id'   => 1, // Ubah sesuai posisi jika ada data relasi
        // ]);

        // DB::table('students')->insert([
        //     'id_student'        => 'STD002',
        //     'fullname'          => 'Junaedi',
        //     'class_id'          => 1,
        //     'parent_phonecell'  => '081234567890',
        //     'photo'             => null, // atau masukkan nama file jika ada
        //     'birth_place'       => 'Bandung',
        //     'birth_date'        => '2007-04-15',
        //     'gender'            => 'L',
        //     'password'          => Hash::make('student123'),
        //     'created_at'        => now(),
        //     'updated_at'        => now(),
        //     'academic_year_id' => 9,
        //     'semester_id'      => 1,
        // ]);

        // DB::table('book_loans')->insert([
        //     'id_student' => 'STD002',
        //     'book_id' => 6,
        //     'loan_date' => Carbon::create('2025', '05', '01'),
        //     'due_date' => Carbon::create('2025', '05', '15'),
        //     'return_date' => Carbon::create('2025', '05', '10'),
        //     'status' => 'returned',
        //     'academic_year_id' => 9,
        //     'semester_id' => 1,
        // ]);

        // DB::table('student_achievements')->insert([
        //     'id_student' => 'STD002',
        //     'subject_id' => 1,
        //     'score' => 90,
        //     'semester_id' => 1,
        //     'student_rank' => 2,
        //     'remark' => 'Mantap Teruskan Ya '
        // ]);

        DB::table('rapor')->insert([
            'id_student' => 'STD002',
            'class_id' => 1,
            'academic_year_id' => 9,
            'semester_id' => 1,
            'report_date' => Carbon::create('2025', '06', '01'),
            'file_path' => 'Rapor.pdf', // atau masukkan nama file jika ada
            'description' => 'Rapor Semester Genap'
        ]);
    }
}
