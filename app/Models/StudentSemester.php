<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class StudentSemester extends Pivot
{
    use HasFactory;

    protected $table = 'student_semester';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'class_id',
        'academic_year_id',
        'semester_id',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id_student');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
