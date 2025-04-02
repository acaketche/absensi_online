<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_employee', 'status_id', 'attendance_date', 'check_in',
        'check_out', 'latitude_in', 'longitude_in', 'latitude_out', 'longitude_out',
    ];

    public function employee()
{
    return $this->belongsTo(Employee::class, 'id_employee');
}

public function status()
{
    return $this->belongsTo(Status::class, 'status_id');
}

}
