<?php

namespace Database\Seeders;

use App\Models\StudentAttendance;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentAttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        foreach (range(1, 50) as $i) {
            StudentAttendance::create([
                'id_student'        => 'STD002',
                'class_id'          => 2,
                'subject_id'        => 1,
                'attendance_date'   => \Carbon\Carbon::now()->subDays(rand(0, 10))->toDateString(),
                'attendance_time'   => $faker->time('H:i:s'),
                'check_in_time'     => $faker->time('H:i:s'),
                'check_out_time'    => $faker->optional()->time('H:i:s'),
                'status_id'         => 1,
                'latitude'          => $faker->latitude(-7.8, -7.5),
                'longitude'         => $faker->longitude(110.3, 110.6),
                'academic_year_id'  => 9,
                'semester_id'       => 1,
                'document'          => $faker->optional()->imageUrl(),
            ]);
        }
    }
}
