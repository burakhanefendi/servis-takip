<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'firma_adi',
        'telefon_1',
        'telefon_2',
        'email',
        'website',
        'adres',
        'il',
        'ilce',
        'logo'
    ];
    
    // Ayarları getir (singleton pattern)
    public static function getSettings()
    {
        return self::first() ?? new self();
    }
}
