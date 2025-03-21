<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $table = 'holidays'; // Nama tabel dalam database
    protected $primaryKey = 'id'; // Primary key tabel

    protected $fillable = ['holiday_date', 'description', 'academic_year_id']; // Kolom yang bisa diisi

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}

