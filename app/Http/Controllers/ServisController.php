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
                'durum' => 'İşlemde',
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
        // İstatistikler
        $stats = [
            'acik' => Servis::whereIn('durum', ['Beklemede', 'İşlemde'])->count(),
            'tamamlanan' => Servis::where('durum', 'Tamamlandı')->count(),
            'iptal' => Servis::where('durum', 'İptal')->count(),
            'bakim' => Servis::whereNotNull('periyodik_bakim')->whereNotNull('bakim_tarihi')->count(),
        ];

        $query = Servis::with('cariHesap');

        // Kategori filtresi (yeni)
        $kategori = $request->get('kategori', 'acik'); // Varsayılan: açık servisler
        
        if ($kategori == 'acik') {
            $query->whereIn('durum', ['Beklemede', 'İşlemde']);
        } elseif ($kategori == 'tamamlanan') {
            $query->where('durum', 'Tamamlandı');
        } elseif ($kategori == 'iptal') {
            $query->where('durum', 'İptal');
        } elseif ($kategori == 'bakim') {
            $query->whereNotNull('periyodik_bakim')->whereNotNull('bakim_tarihi');
        }

        // Durum filtresi (eski - backward compatibility)
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
        return view('servis.index', compact('servisler', 'stats', 'kategori'));
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

    // Bakım Listesi
    public function bakimListesi(Request $request)
    {
        $query = Servis::with('cariHesap')
            ->whereNotNull('periyodik_bakim')
            ->where('durum', 'Tamamlandı');

        // Periyodik bakım filtresi
        if ($request->filled('periyodik_bakim')) {
            $query->where('periyodik_bakim', $request->periyodik_bakim);
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

        // Bakım tarihi durumuna göre sıralama
        $servisler = $query->orderBy('bakim_tarihi', 'asc')->paginate(20);
        
        // Her servise "durum" ekle (geçmiş/yaklaşan/normal)
        $servisler->getCollection()->transform(function ($servis) {
            $bakimTarihi = $servis->bakim_tarihi;
            $today = now();
            
            if ($bakimTarihi) {
                $gunFarki = $today->diffInDays($bakimTarihi, false);
                
                if ($gunFarki < 0) {
                    $servis->bakim_durum = 'gecmis'; // Geçmiş (kırmızı)
                } elseif ($gunFarki <= 7) {
                    $servis->bakim_durum = 'yaklasan'; // Yaklaşan (sarı)
                } else {
                    $servis->bakim_durum = 'normal'; // Normal (yeşil)
                }
                
                $servis->gun_farki = abs($gunFarki);
            } else {
                $servis->bakim_durum = 'belirsiz';
                $servis->gun_farki = null;
            }
            
            return $servis;
        });

        return view('bakim.index', compact('servisler'));
    }

    // Manuel Bakım Ekle - Form
    public function bakimCreate()
    {
        return view('bakim.create');
    }

    // Manuel Bakım Kaydet
    public function bakimStore(Request $request)
    {
        $request->validate([
            'cari_hesap_id' => 'required|exists:cari_hesaplar,id',
            'marka' => 'required|string',
            'model' => 'required|string',
            'ilk_bakim_tarihi' => 'required|date',
            'periyodik_bakim' => 'required|string',
            'personel' => 'nullable|string',
            'bakim_icerigi' => 'nullable|string',
            'bakim_lokasyonu' => 'nullable|string',
            'hatirlatma_zamani' => 'nullable|date',
        ]);

        DB::beginTransaction();
        try {
            // Sonraki bakım tarihini hesapla
            $ilkBakimTarihi = \Carbon\Carbon::parse($request->ilk_bakim_tarihi);
            $sonrakiBakimTarihi = null;

            switch($request->periyodik_bakim) {
                case 'Aylık':
                    $sonrakiBakimTarihi = $ilkBakimTarihi->copy()->addMonth();
                    break;
                case '3 Aylık':
                    $sonrakiBakimTarihi = $ilkBakimTarihi->copy()->addMonths(3);
                    break;
                case '6 Aylık':
                    $sonrakiBakimTarihi = $ilkBakimTarihi->copy()->addMonths(6);
                    break;
                case 'Yıllık':
                    $sonrakiBakimTarihi = $ilkBakimTarihi->copy()->addYear();
                    break;
                case '2 Yıllık':
                    $sonrakiBakimTarihi = $ilkBakimTarihi->copy()->addYears(2);
                    break;
                case 'Bir Kez':
                    $sonrakiBakimTarihi = null;
                    break;
            }

            $servis = Servis::create([
                'servis_no' => Servis::generateServisNo(),
                'cari_hesap_id' => $request->cari_hesap_id,
                'marka' => $request->marka,
                'model' => $request->model,
                'personel' => $request->personel,
                'ilk_bakim_tarihi' => $request->ilk_bakim_tarihi,
                'periyodik_bakim' => $request->periyodik_bakim,
                'bakim_tarihi' => $sonrakiBakimTarihi,
                'bakim_icerigi' => $request->bakim_icerigi,
                'bakim_lokasyonu' => $request->bakim_lokasyonu,
                'sms_hatirlatma' => $request->has('sms_hatirlatma') ? true : false,
                'hatirlatma_zamani' => $request->hatirlatma_zamani,
                'durum' => 'Tamamlandı', // İlk bakım yapılmış sayılır
                'tamamlanma_tarihi' => $request->ilk_bakim_tarihi,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bakım kaydı başarıyla oluşturuldu!',
                'redirect' => route('bakim.index')
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
