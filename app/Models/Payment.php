<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments'; // Nama tabel

    protected $fillable = [
        'id_student',
        'academic_year_id',
        'amount',
        'status',
        'last_update',
        'notes'
    ];

    // Relasi ke Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'id_student', 'id_student');
    }

    // Relasi ke Tahun Ajaran
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }
}
