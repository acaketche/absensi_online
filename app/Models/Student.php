<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang digunakan
    protected $table = 'students';

    protected $primaryKey = 'id_student';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_student',
        'fullname',
        'class_id',
        'parent_phonecell',
        'photo',
        'password',
        'birth_place',
        'birth_date',
        'gender',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    // public function grades()
    // {
    //     return $this->hasMany(Grade::class, 'student_id', 'id_student');
    // }

    // public function getFullName()
    // {
    //     return $this->fullname;
    // }
}
