<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;

class PasswordHashSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update password for a specific student
        $student = Student::where('id_student', 'THE_STUDENT_ID')->first();
        if ($student) {
            // Assign plain password to trigger mutator hashing
            $student->password = 'THE_NEW_PASSWORD';
            $student->save();
            echo "Password updated for student: " . $student->id_student . "\\n";
        } else {
            echo "Student not found.\\n";
        }
    }
}
