<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $table = 'hero_section';

    protected $fillable = [
        'foto_hero',
        'foto_ornamen',
        'judul',
        'status',
    ];
}
