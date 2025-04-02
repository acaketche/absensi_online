<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects'; // Nama tabel di database

    protected $primaryKey = 'subject_id'; // Primary Key

    public $timestamps = true; // Mengaktifkan timestamps (created_at, updated_at)

    protected $fillable = [
        'subject_name',
        'subject_code',
        'academic_year_id',
        'semester_id'
    ]; // Kolom yang boleh diisi

    /**
     * Relasi ke tabel AcademicYear
     */
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    /**
     * Relasi ke tabel Semester
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
