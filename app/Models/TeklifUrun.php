<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeklifUrun extends Model
{
    use HasFactory;

    protected $table = 'teklif_urunler';

    protected $fillable = [
        'teklif_id',
        'sku',
        'stok_tanimi',
        'miktar',
        'birim',
        'birim_fiyat',
        'indirim_oran',
        'indirim_tutar',
        'kdv_oran',
        'kdv_tutar',
        'tevkifat',
        'tutar_kdv_haric',
        'tutar_kdv_dahil',
        'depo',
        'sira_no'
    ];

    protected $casts = [
        'miktar' => 'decimal:2',
        'birim_fiyat' => 'decimal:2',
        'indirim_oran' => 'decimal:2',
        'indirim_tutar' => 'decimal:2',
        'kdv_oran' => 'decimal:2',
        'kdv_tutar' => 'decimal:2',
        'tevkifat' => 'decimal:2',
        'tutar_kdv_haric' => 'decimal:2',
        'tutar_kdv_dahil' => 'decimal:2',
    ];

    // İlişkiler
    public function teklif()
    {
        return $this->belongsTo(Teklif::class, 'teklif_id');
    }

    // Helper method: Tutarları hesapla
    public function calculateAmounts()
    {
        // Brüt tutar
        $brutTutar = $this->miktar * $this->birim_fiyat;
        
        // İndirim tutarı
        $this->indirim_tutar = $brutTutar * ($this->indirim_oran / 100);
        
        // KDV hariç tutar
        $this->tutar_kdv_haric = $brutTutar - $this->indirim_tutar;
        
        // KDV tutarı
        $this->kdv_tutar = $this->tutar_kdv_haric * ($this->kdv_oran / 100);
        
        // KDV dahil tutar
        $this->tutar_kdv_dahil = $this->tutar_kdv_haric + $this->kdv_tutar;
        
        $this->save();
    }
}
