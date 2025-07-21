<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PicketSchedule extends Model
{
    use HasFactory;

    protected $table = 'picket_schedules';

    protected $fillable = [
        'employee_id',
        'picket_date',
    ];

    // Relasi ke model Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id_employee');
    }
}
