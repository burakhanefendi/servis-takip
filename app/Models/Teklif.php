<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teklif extends Model
{
    use HasFactory;

    protected $table = 'teklifler';

    protected $fillable = [
        'teklif_no',
        'cari_hesap_id',
        'baslik',
        'baslangic_tarihi',
        'gecerlilik_tarihi',
        'para_birimi',
        'fotograflar_goster',
        'mal_hizmet_tutari',
        'toplam_iskonto',
        'ara_toplam',
        'hesaplanan_kdv',
        'genel_toplam',
        'durum',
        'notlar'
    ];

    protected $casts = [
        'baslangic_tarihi' => 'date',
        'gecerlilik_tarihi' => 'date',
        'fotograflar_goster' => 'boolean',
        'mal_hizmet_tutari' => 'decimal:2',
        'toplam_iskonto' => 'decimal:2',
        'ara_toplam' => 'decimal:2',
        'hesaplanan_kdv' => 'decimal:2',
        'genel_toplam' => 'decimal:2',
    ];

    // İlişkiler
    public function cariHesap()
    {
        return $this->belongsTo(CariHesap::class, 'cari_hesap_id');
    }

    public function urunler()
    {
        return $this->hasMany(TeklifUrun::class, 'teklif_id')->orderBy('sira_no');
    }

    // Helper method: Yeni teklif no oluştur
    public static function generateTeklifNo()
    {
        $lastTeklif = self::orderBy('id', 'desc')->first();
        $lastNo = $lastTeklif ? intval(substr($lastTeklif->teklif_no, 3)) : 0;
        return 'TKL' . str_pad($lastNo + 1, 5, '0', STR_PAD_LEFT);
    }

    // Helper method: Toplamları hesapla
    public function calculateTotals()
    {
        $urunler = $this->urunler;
        
        $this->mal_hizmet_tutari = $urunler->sum(function($urun) {
            return $urun->miktar * $urun->birim_fiyat;
        });
        
        $this->toplam_iskonto = $urunler->sum('indirim_tutar');
        $this->ara_toplam = $urunler->sum('tutar_kdv_haric');
        $this->hesaplanan_kdv = $urunler->sum('kdv_tutar');
        $this->genel_toplam = $urunler->sum('tutar_kdv_dahil');
        
        $this->save();
    }
}
