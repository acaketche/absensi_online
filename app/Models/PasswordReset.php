<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $table = 'password_resets';
    public $timestamps = false; // Karena `created_at` sudah ada tanpa `updated_at`

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
