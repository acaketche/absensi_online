<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'author',
        'publisher',
        'year_published',
        'stock'
    ];

    protected $casts = [
        'year_published' => 'integer',
        'stock' => 'integer'
    ];
        public function loans()
    {
        return $this->hasMany(BookLoan::class, 'book_id');
    }
}
