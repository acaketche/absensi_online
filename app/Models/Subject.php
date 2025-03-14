<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'subjects'; // Nama tabel di database

    protected $primaryKey = 'subject_id'; // Primary Key

    public $timestamps = true; // Mengaktifkan timestamps (created_at, updated_at)

    protected $fillable = ['subject_name']; // Kolom yang boleh diisi

}
