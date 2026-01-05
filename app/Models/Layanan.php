<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    // Tentukan nama tabel
    protected $table = 'layanan';

    protected $fillable = [
        'foto_layanan',
        'nama_layanan',
        'deskripsi_layanan',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Validation rules
    public static function getValidationRules($isUpdate = false)
    {
        $rules = [
            'nama_layanan' => 'required|string|max:250',
            'deskripsi_layanan' => 'required|string|max:350',
            'status' => 'required|boolean',
        ];

        if (!$isUpdate) {
            $rules['foto_layanan'] = 'required|image|mimes:jpeg,png,jpg,gif|max:5120';
        } else {
            $rules['foto_layanan'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120';
        }

        return $rules;
    }
}
