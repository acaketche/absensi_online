<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

   protected $fillable = [
    'id_employee',
    'status_id',
    'attendance_date',
    'check_in',
    'check_out',
    'academic_year_id',
    'semester_id',
];

    public function employee()
{
    return $this->belongsTo(Employee::class, 'id_employee');
}

public function status()
{
    return $this->belongsTo(AttendanceStatus::class, 'status_id');
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
