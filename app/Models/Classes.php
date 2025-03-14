<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $primaryKey = 'class_id'; // Sesuaikan dengan nama kolom yang benar
    public $incrementing = false;
    protected $fillable = ['class_name', 'id_employee'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
}
