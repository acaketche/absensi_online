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
        'stock',
        'cover',
        'class_id', // <--- tambahkan ini
    ];

    protected $casts = [
        'year_published' => 'integer',
        'stock' => 'integer',
    ];

    public function loans()
    {
        return $this->hasMany(BookLoan::class, 'book_id');
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function available_copies()
    {
        return $this->hasMany(BookCopy::class)->where('is_available', 1);
    }

   public function class()
{
    return $this->belongsTo(Classes::class, 'class_id', 'class_id');
}

}
