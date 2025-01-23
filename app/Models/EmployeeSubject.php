<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSubject extends Model
{
    use HasFactory;

    protected $fillable = ['id_employee', 'subject_id'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
