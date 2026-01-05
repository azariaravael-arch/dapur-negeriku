<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';

    protected $fillable = [
        'nama_pengguna',
        'email_pengguna',
        'password_admin',
        'foto_pengguna',
        'status',
    ];

    protected $hidden = [
        'password_admin',
    ];

    public function getAuthPassword()
    {
        return $this->password_admin;
    }
}
