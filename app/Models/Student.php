<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'students';
    protected $primaryKey = 'id_student';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'id_student', 'fullname', 'class_id', 'parent_phonecell', 'photo', 'password',
        'birth_place', 'birth_date', 'gender', 'academic_year_id', 'semester_id'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    // Relasi ke Kelas
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'class_id');
    }

    // Relasi ke Tahun Ajaran
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    // Relasi ke Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // Hash password sebelum menyimpan
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

}
