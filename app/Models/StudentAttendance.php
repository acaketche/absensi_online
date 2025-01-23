<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student', 'class_id', 'subject_id', 'attendance_date',
        'attendance_time', 'status_id', 'latitude', 'longitude',
    ];
}
