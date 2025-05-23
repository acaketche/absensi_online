<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $table = 'student_attendances'; // Nama tabel di database

    protected $fillable = [
        'id_student',
        'class_id',
        'attendance_date',
        'attendance_time',
        'check_in_time',
        'check_out_time',
        'status_id',
        'latitude',
        'longitude',
        'academic_year_id',
        'semester_id',
        'document'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student', 'id_student');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    public function status()
    {
        return $this->belongsTo(AttendanceStatus::class, 'status_id');
    }
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    // Relasi ke Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
