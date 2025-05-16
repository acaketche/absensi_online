<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class HashStudentPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hash:studentpasswords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash plain text passwords of students in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $students = Student::all();
        $count = 0;

        foreach ($students as $student) {
            $password = $student->password;

            // Check if password is already hashed (bcrypt hashes start with $2y$)
            if (strpos($password, '$2y$') !== 0) {
                $student->password = Hash::make($password);
                $student->save();
                $count++;
                $this->info("Hashed password for student: {$student->id_student}");
            }
        }

        $this->info("Total passwords hashed: $count");
    }
}
