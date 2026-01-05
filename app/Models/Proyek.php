<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    use HasFactory;

    protected $table = 'proyek';

    protected $fillable = [
        'foto_proyek',
        'nama_proyek',
        'deskripsi_proyek',
        'foto_tambahan_proyek',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // TAMBAHKAN INI jika tidak ingin menggunakan timestamps
    public $timestamps = false;
}
