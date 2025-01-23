<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_student';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_student', 'fullname', 'class_id', 'parent_phonecell', 'photo', 'password',
    ];

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
