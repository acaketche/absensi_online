<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_student', 'book_id', 'loan_date', 'due_date', 'return_date', 'status',
    ];
}
