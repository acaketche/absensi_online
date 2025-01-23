<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = ['class_name', 'id_employee'];

    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
}
