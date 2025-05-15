<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceStatus extends Model
{
    use HasFactory;

    protected $table = 'attendance_status';

    protected $primaryKey = 'status_id';

    public $timestamps = true;

    protected $fillable = [
        'status_name'
    ];

    public function studentAttendances()
    {
        $this->hasMany(StudentAttendance::class, 'status_id');
    }
}
