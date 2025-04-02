<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = ['academic_year_id', 'semester_name', 'start_date', 'end_date', 'is_active'];

    // Relasi ke Tahun Akademik
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'semester_id');
    }

    // Scope untuk semester aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'semester_id');
    }
}
