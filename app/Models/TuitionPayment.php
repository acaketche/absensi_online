<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student', 'payment_date', 'amount_paid', 'total_due',
        'status_id', 'academic_year_id',
    ];
}
