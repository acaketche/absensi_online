<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student', 'subject_id', 'academic_year_id', 'semester',
        'score', 'student_rank', 'remarks',
    ];
}
