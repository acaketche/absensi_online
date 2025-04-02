<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'employees'; // Tambahkan ini agar Laravel tahu tabelnya

    protected $primaryKey = 'id_employee';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_employee', 'fullname', 'birth_place', 'birth_date',
        'gender', 'phone_number', 'email', 'role_id',
        'password', 'photo', 'qr_code'
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
