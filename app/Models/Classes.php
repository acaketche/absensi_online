<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $primaryKey = 'class_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'class_name',
        'class_level',
        'id_employee',
        'academic_year_id',
        'semester_id'
    ];

    // Relasi ke Employee (Wali Kelas)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    // Relasi ke Student (Siswa dalam kelas)
    public function students()
{
    return $this->belongsToMany(Student::class, 'student_semester', 'class_id', 'student_id')
        ->withPivot('academic_year_id', 'semester_id');
}

    public function currentStudents()
    {
        return $this->students()
            ->wherePivot('academic_year_id', AcademicYear::active()->id)
            ->wherePivot('semester_id', Semester::active()->id);
    }

    // Relasi ke Tahun Akademik
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    // Relasi ke Semester - NEWLY ADDED
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }

    public function spp()
    {
        return $this->hasMany(Spp::class, 'class_id', 'class_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function studentSemesters()
    {
        return $this->hasMany(StudentSemester::class, 'class_id', 'class_id');
    }
    public function waliKelas()
{
    return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
}

}
