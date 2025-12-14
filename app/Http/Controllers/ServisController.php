<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Servis;
use App\Models\ServisUrun;
use App\Models\CariHesap;
use Illuminate\Support\Facades\DB;

class ServisController extends Controller
{
    // Servis ekle sayfasını göster
    public function create()
    {
        $servisNo = Servis::generateServisNo();
        return view('servis.create', compact('servisNo'));
    }

    // Cari arama API
    public function searchCari(Request $request)
    {
        $search = $request->get('search', '');
        
        if (strlen($search) < 3) {
            return response()->json([]);
        }

        $cariList = CariHesap::where('cari_hesap_adi', 'LIKE', "%{$search}%")
            ->orWhere('musteri_kodu', 'LIKE', "%{$search}%")
            ->orWhere('kisa_isim', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get(['id', 'cari_hesap_adi', 'musteri_kodu', 'eposta', 'gsm', 'sabit_telefon', 'il', 'ilce', 'adres']);

        return response()->json($cariList);
    }

    // Servis kaydet
    public function store(Request $request)
    {
        $request->validate([
            'cari_hesap_id' => 'required|exists:cari_hesaplar,id',
            'servis_no' => 'required|unique:servisler,servis_no',
        ]);

        DB::beginTransaction();
        try {
            $servis = Servis::create([
                'servis_no' => $request->servis_no,
                'cari_hesap_id' => $request->cari_hesap_id,
                'eposta' => $request->eposta,
                'gsm' => $request->gsm,
                'sabit_telefon' => $request->sabit_telefon,
                'il' => $request->il,
                'ilce' => $request->ilce,
                'adres' => $request->adres,
                'marka' => $request->marka,
                'model' => $request->model,
                'seri_numarasi' => $request->seri_numarasi,
                'urun_cinsi' => $request->urun_cinsi,
                'model_kodu' => $request->model_kodu,
                'urun_rengi' => $request->urun_rengi,
                'garanti_durumu' => $request->garanti_durumu,
                'fatura_numarasi' => $request->fatura_numarasi,
                'fatura_tarihi' => $request->fatura_tarihi,
                'urunun_fiziksel_durumu' => $request->urunun_fiziksel_durumu,
                'oncelik_durumu' => $request->oncelik_durumu,
                'musterinin_sikayeti' => $request->musterinin_sikayeti,
                'ariza_tanimi' => $request->ariza_tanimi,
                'teknisyenin_yorumu' => $request->teknisyenin_yorumu,
                'tahmini_ucret' => $request->tahmini_ucret,
                'teslimat_turu' => $request->teslimat_turu,
                'kargo_sirket' => $request->kargo_sirket,
                'tahmini_bitis_tarihi' => $request->tahmini_bitis_tarihi,
                'personel' => $request->personel,
                'randevu_tarihi' => $request->randevu_tarihi,
                'durum' => 'Beklemede',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servis kaydı başarıyla oluşturuldu!',
                'redirect' => route('servis.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Servis listesi (Servis Durumu)
    public function index(Request $request)
    {
        $query = Servis::with('cariHesap');

        // Durum filtresi
        if ($request->filled('durum')) {
            $query->where('durum', $request->durum);
        }

        // Arama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('servis_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('cariHesap', function($q) use ($search) {
                      $q->where('cari_hesap_adi', 'LIKE', "%{$search}%");
                  });
            });
        }

        $servisler = $query->latest()->paginate(20);
        return view('servis.index', compact('servisler'));
    }

    // Servis detay görüntüle
    public function show($id)
    {
        $servis = Servis::with(['cariHesap', 'urunler'])->findOrFail($id);
        return view('servis.show', compact('servis'));
    }

    // Servis tamamlama formu
    public function complete($id)
    {
        $servis = Servis::with(['cariHesap', 'urunler'])->findOrFail($id);
        
        // Sadece beklemede olan servisler tamamlanabilir
        if ($servis->durum === 'Tamamlandı') {
            return redirect()->route('servis.show', $id)
                ->with('error', 'Bu servis zaten tamamlanmış!');
        }

        return view('servis.complete', compact('servis'));
    }

    // Servis tamamlama işlemi
    public function finish(Request $request, $id)
    {
        $servis = Servis::findOrFail($id);

        $request->validate([
            'yapilan_islemler' => 'nullable|string',
            'servis_sonucu' => 'nullable|string',
            'periyodik_bakim' => 'nullable|string',
            'odeme_yontemi' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Ürünleri kaydet
            if ($request->has('urunler')) {
                // Mevcut ürünleri sil
                $servis->urunler()->delete();
                
                foreach ($request->urunler as $urun) {
                    if (!empty($urun['stok_adi'])) {
                        ServisUrun::create([
                            'servis_id' => $servis->id,
                            'stok_adi' => $urun['stok_adi'],
                            'stok_kodu' => $urun['stok_kodu'] ?? null,
                            'miktar' => $urun['miktar'] ?? 1,
                            'birim' => $urun['birim'] ?? 'ADET',
                            'para_birimi' => $urun['para_birimi'] ?? '₺ Türk Lirası',
                            'birim_fiyat' => $urun['birim_fiyat'] ?? 0,
                            'kdv_orani' => $urun['kdv_orani'] ?? 20,
                            'kdv_tutari' => $urun['kdv_tutari'] ?? 0,
                            'toplam_kdv_haric' => $urun['toplam_kdv_haric'] ?? 0,
                            'toplam_kdv_dahil' => $urun['toplam_kdv_dahil'] ?? 0,
                            'depo' => $urun['depo'] ?? 'Varsayılan',
                        ]);
                    }
                }
            }

            // Servis bilgilerini güncelle
            $servis->update([
                'yapilan_islemler' => $request->yapilan_islemler,
                'toplam_mal_hizmet_tutari' => $request->toplam_mal_hizmet_tutari,
                'toplam_iskonto' => $request->toplam_iskonto ?? 0,
                'hesaplanan_kdv' => $request->hesaplanan_kdv ?? 0,
                'vergiler_dahil_toplam' => $request->vergiler_dahil_toplam,
                'servis_sonucu' => $request->servis_sonucu,
                'periyodik_bakim' => $request->periyodik_bakim,
                'bakim_tarihi' => $request->bakim_tarihi,
                'sms_hatirlatma' => $request->has('sms_hatirlatma') ? true : false,
                'islem_garantisi' => $request->islem_garantisi,
                'odeme_yontemi' => $request->odeme_yontemi,
                'durum' => 'Tamamlandı',
                'tamamlanma_tarihi' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Servis başarıyla tamamlandı!',
                'redirect' => route('servis.show', $servis->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
