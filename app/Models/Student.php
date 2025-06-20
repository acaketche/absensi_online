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
    protected $guard = 'student';
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

    public function rapor()
    {
        return $this->hasMany(Rapor::class, 'id_student', 'id_student');
    }


    public function bookLoans()
    {
        return $this->hasMany(BookLoan::class, 'id_student', 'id_student');
    }

    public function borrowedBooks()
    {
        return $this->hasMany(BookLoan::class, 'id_student', 'id_student')
            ->whereNull('return_date'); // buku yg sedang dipinjam (belum dikembalikan)
    }

    public function returnedBooks()
    {
        return $this->hasMany(BookLoan::class, 'id_student', 'id_student')
            ->whereNotNull('return_date'); // buku yg sudah dikembalikan
    }


    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class, 'id_student', 'id_student');
    }
   public function payments()
{
    return $this->hasMany(Payment::class, 'id_student', 'id_student');
}

}
