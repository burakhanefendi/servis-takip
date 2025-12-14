<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YetkiliKisi extends Model
{
    protected $table = 'yetkili_kisiler';

    protected $fillable = [
        'cari_hesap_id',
        'ad_soyad',
        'unvan',
        'telefon',
        'eposta',
        'dahili',
    ];

    public function cariHesap()
    {
        return $this->belongsTo(CariHesap::class);
    }
}
