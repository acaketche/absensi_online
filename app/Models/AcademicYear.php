<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = ['year_name', 'semester', 'start_date', 'end_date', 'is_active'];

    // Definisi nilai default untuk semester
    public function semesters()
    {
        return $this->hasMany(Semester::class, 'academic_year_id');
    }
    public function students()
    {
        return $this->hasMany(Student::class, 'academic_year_id');
    }

    // Scope untuk tahun ajaran aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'academic_year_id');
    }
}
