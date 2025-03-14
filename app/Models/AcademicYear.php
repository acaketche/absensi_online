<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['year_name', 'start_date', 'end_date', 'is_active'];

    public function semesters()
    {
        return $this->hasMany(Semester::class, 'academic_year_id');
    }
}
