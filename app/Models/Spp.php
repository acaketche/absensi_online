<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    protected $table = 'spp';

    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $keyType = 'string';

    // Set to true jika kamu ingin Laravel mengelola 'created_at' dan 'updated_at'
    public $timestamps = false;  // Jika kamu tidak ingin otomatis, tetap false

    protected $fillable = [
        'id',
        'academic_year_id',
        'semester_id',
        'class_id',
        'amount',
        'created_at',
        'updated_at', // Tambahkan updated_at
    ];

    // Bisa menambahkan mutator untuk otomatis mengisi `updated_at`
    // Misal ketika kamu update atau buat data baru, bisa otomatis mengisi
    protected $dates = ['created_at', 'updated_at'];

 public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }
    public function semester()
{
    return $this->belongsTo(Semester::class, 'semester_id');
}
public function classes()
{
    return $this->belongsTo(Classes::class, 'class_id');
}

}
