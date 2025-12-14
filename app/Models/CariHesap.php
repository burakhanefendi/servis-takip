<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CariHesap extends Model
{
    protected $table = 'cari_hesaplar';

    protected $fillable = [
        'cari_hesap_adi',
        'musteri_kodu',
        'kisa_isim',
        'cari_group_id',
        'vergi_dairesi',
        'vergi_kimlik_no',
        'iban',
        'il',
        'ilce',
        'adres',
        'sevk_adresi',
        'gsm',
        'eposta',
        'sabit_telefon',
    ];

    // İlişkiler
    public function cariGroup()
    {
        return $this->belongsTo(CariGroup::class);
    }

    public function yetkiliKisiler()
    {
        return $this->hasMany(YetkiliKisi::class);
    }

    // Otomatik müşteri kodu oluştur
    public static function generateMusteriKodu()
    {
        $lastCari = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastCari ? intval(substr($lastCari->musteri_kodu, 3)) : 0;
        return 'MUS' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    }
}
