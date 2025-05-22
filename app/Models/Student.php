<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens;

    protected $table = 'students';
    protected $primaryKey = 'id_student';
    public $incrementing = false;
    public $timestamps = true;


protected $fillable = [
    'id_student',
    'fullname',
    'password',
    'birth_place',
    'birth_date',
    'gender',
    'parent_phonecell',
    'class_id',
    'academic_year_id',
    'semester_id',
    'photo',
    'qrcode'
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
    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class, 'id_student', 'id_student');
    }
    public function rapor()
    {
        return $this->hasOne(Rapor::class, 'id_student', 'id_student');
    }

    public function overdueLoans()
        {
            return $this->hasMany(BookLoan::class, 'id_student', 'id_student')
                        ->where('due_date', '<', now())
                        ->whereNull('return_date'); // pastikan ada kolom returned_at untuk peminjaman yang belum dikembalikan
        }

public function attendances()
{
    return $this->hasMany(StudentAttendance::class, 'id_student', 'id_student');
}


}
