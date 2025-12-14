<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServisUrun extends Model
{
    protected $table = 'servis_urunler';

    protected $fillable = [
        'servis_id',
        'stok_adi',
        'stok_kodu',
        'miktar',
        'birim',
        'para_birimi',
        'birim_fiyat',
        'kdv_orani',
        'kdv_tutari',
        'toplam_kdv_haric',
        'toplam_kdv_dahil',
        'depo',
    ];

    protected $casts = [
        'miktar' => 'decimal:2',
        'birim_fiyat' => 'decimal:2',
        'kdv_orani' => 'decimal:2',
        'kdv_tutari' => 'decimal:2',
        'toplam_kdv_haric' => 'decimal:2',
        'toplam_kdv_dahil' => 'decimal:2',
    ];

    // İlişkiler
    public function servis()
    {
        return $this->belongsTo(Servis::class);
    }
}
