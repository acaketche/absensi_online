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
        'photo',
        'qrcode'
    ];


    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function rapor()
    {
        return $this->hasMany(Rapor::class, 'id_student', 'id_student');
    }

public function classes()
{
    return $this->belongsToMany(Classes::class, 'student_semester', 'student_id', 'class_id')
        ->withPivot('academic_year_id', 'semester_id');
}


public function activeClass()
{
    return $this->classes()
        ->wherePivot('academic_year_id', AcademicYear::active()->id)
        ->wherePivot('semester_id', Semester::active()->id)
        ->first();
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
public function studentSemesters()
{
    return $this->hasMany(StudentSemester::class, 'student_id', 'id_student');
}
}
