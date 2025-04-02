<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapor extends Model
{
    use HasFactory;

    protected $table = 'rapor'; // Nama tabel di database

    protected $primaryKey = 'id'; // Primary Key

    public $timestamps = true; // Mengaktifkan timestamps

    protected $fillable = [
        'id_student',
        'class_id',
        'academic_year_id',
        'semester_id',
        'report_date',
        'file_path',
        'description'
    ];

    /**
     * Relasi ke tabel Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student', 'id_student');
    }

    /**
     * Relasi ke tabel Class
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'class_id');
    }

    /**
     * Relasi ke tabel Academic Year
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    /**
     * Relasi ke tabel Semester
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }
}
