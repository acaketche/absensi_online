<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $table = 'book_copies'; // opsional jika nama model ≠ nama tabel

    protected $fillable = [
        'book_id',
        'copy_code',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean', // supaya nilai 1/0 otomatis jadi true/false
    ];

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
    public function getStatusAttribute()
{
    return $this->is_available ? 'Tersedia' : 'Dipinjam';
}
public function loans()
{
    return $this->hasMany(BookLoan::class, 'copy_id');
}

}
