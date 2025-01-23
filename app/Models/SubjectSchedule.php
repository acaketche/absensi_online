<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_at_week', 'start_time', 'end_time', 'class_id', 'subject_id',
        'id_employee', 'academic_year_id', 'latitude', 'longitude', 'radius',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}
