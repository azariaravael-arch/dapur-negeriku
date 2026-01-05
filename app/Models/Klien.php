<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klien extends Model
{
    use HasFactory;

    protected $table = 'klien';

    protected $fillable = [
        'foto_klient',
        'nama_klient',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // TAMBAHKAN INI jika tidak ingin menggunakan timestamps
    public $timestamps = false;
}
