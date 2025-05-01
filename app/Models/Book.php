<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'code', // ✅ ditambahkan
        'title',
        'author',
        'publisher',
        'year_published',
        'stock',
    ];

    public function loans()
    {
        return $this->hasMany(BookLoan::class, 'book_id');
    }
}
