<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPasswordTrait;

    protected $table = 'employees';
    protected $guard = 'employee';
    protected $primaryKey = 'id_employee';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_employee', 'fullname', 'birth_place', 'birth_date',
        'gender', 'phone_number', 'email', 'role_id',
        'position_id', 'password', 'photo', 'qr_code'
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class, 'id_employee', 'id_employee');
    }

    public function kelasAsuh()
    {
        return $this->hasOne(Classes::class, 'id_employee', 'id_employee');
    }
public function picketSchedules()
{
    return $this->hasMany(PicketSchedule::class);
}

}
