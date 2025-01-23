<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_employee';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_employee', 'fullname', 'role_id', 'photo', 'qr_code',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
