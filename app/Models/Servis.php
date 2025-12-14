<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    protected $table = 'servisler';

    protected $fillable = [
        'servis_no',
        'cari_hesap_id',
        'eposta',
        'gsm',
        'sabit_telefon',
        'il',
        'ilce',
        'adres',
        'marka',
        'model',
        'seri_numarasi',
        'urun_cinsi',
        'model_kodu',
        'urun_rengi',
        'garanti_durumu',
        'fatura_numarasi',
        'fatura_tarihi',
        'urunun_fiziksel_durumu',
        'oncelik_durumu',
        'musterinin_sikayeti',
        'ariza_tanimi',
        'teknisyenin_yorumu',
        'tahmini_ucret',
        'teslimat_turu',
        'kargo_sirket',
        'tahmini_bitis_tarihi',
        'personel',
        'randevu_tarihi',
        'durum',
        // Tamamlama alanları
        'yapilan_islemler',
        'toplam_mal_hizmet_tutari',
        'toplam_iskonto',
        'hesaplanan_kdv',
        'vergiler_dahil_toplam',
        'servis_sonucu',
        'periyodik_bakim',
        'bakim_tarihi',
        'sms_hatirlatma',
        'islem_garantisi',
        'odeme_yontemi',
        'tamamlanma_tarihi',
    ];

    protected $casts = [
        'fatura_tarihi' => 'date',
        'tahmini_bitis_tarihi' => 'date',
        'randevu_tarihi' => 'datetime',
        'tahmini_ucret' => 'decimal:2',
        'toplam_mal_hizmet_tutari' => 'decimal:2',
        'toplam_iskonto' => 'decimal:2',
        'hesaplanan_kdv' => 'decimal:2',
        'vergiler_dahil_toplam' => 'decimal:2',
        'bakim_tarihi' => 'date',
        'sms_hatirlatma' => 'boolean',
        'tamamlanma_tarihi' => 'datetime',
    ];

    // İlişkiler
    public function cariHesap()
    {
        return $this->belongsTo(CariHesap::class);
    }

    public function urunler()
    {
        return $this->hasMany(ServisUrun::class);
    }

    // Otomatik servis numarası oluştur
    public static function generateServisNo()
    {
        $lastServis = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastServis ? intval(substr($lastServis->servis_no, 3)) : 0;
        return 'SRV' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
    }
}
