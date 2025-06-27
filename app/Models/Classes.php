<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $table = 'classes'; // Pastikan sesuai dengan nama tabel
    protected $primaryKey = 'class_id'; // Pastikan nama primary key benar
    public $incrementing = false; // Jika primary key bukan auto-increment
    protected $keyType = 'string'; // Jika class_id bertipe UUID atau string
    public $timestamps = false; // Jika tabel tidak memiliki created_at & updated_at

    protected $fillable = ['class_name', 'id_employee', 'academic_year_id', 'semester_id'];

    // Relasi ke Employee (Wali Kelas)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    // Relasi ke Student (Siswa dalam kelas)
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id', 'class_id');
    }

    // Relasi ke Tahun Akademik
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    // Relasi ke Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id', 'id');
    }
    public function spp()
{
    return $this->hasMany(Spp::class, 'class_id', 'class_id');
}
 public function books()
    {
        return $this->hasMany(Book::class);
    }
}
