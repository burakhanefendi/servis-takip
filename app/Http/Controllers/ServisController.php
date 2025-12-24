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
    public function create(Request $request)
    {
        $servisNo = Servis::generateServisNo();
        $cariHesap = null;
        
        // Eğer cari_id parametresi varsa, cari bilgisini getir
        if ($request->has('cari_id')) {
            $cariHesap = CariHesap::find($request->cari_id);
        }
        
        return view('servis.create', compact('servisNo', 'cariHesap'));
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

            // Bu müşteriye ait "Bekliyor" durumundaki bakımları "İşlemde" yap
            Servis::where('cari_hesap_id', $request->cari_hesap_id)
                ->whereNotNull('periyodik_bakim')
                ->whereNotNull('bakim_tarihi')
                ->where('durum', 'Bekliyor')
                ->update(['durum' => 'İşlemde']);

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
        
        // Bu müşteriye ait aktif bakım takibi var mı kontrol et
        $mevcutBakim = Servis::where('cari_hesap_id', $servis->cari_hesap_id)
            ->whereNotNull('periyodik_bakim')
            ->whereNotNull('bakim_tarihi')
            ->where('bakim_tarihi', '>', now()) // Gelecekteki bakımlar
            ->first();
        
        return view('servis.complete', compact('servis', 'mevcutBakim'));
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

            // Bu müşteriye ait aktif bakım var mı kontrol et
            $mevcutBakim = Servis::where('cari_hesap_id', $servis->cari_hesap_id)
                ->whereNotNull('periyodik_bakim')
                ->whereNotNull('bakim_tarihi')
                ->where('bakim_tarihi', '>', now())
                ->where('id', '!=', $servis->id)
                ->first();
            
            // Eğer mevcut bakım varsa, yeni periyodik bakım bilgisi kaydetme
            $periyodikBakim = $mevcutBakim ? null : $request->periyodik_bakim;
            $bakimTarihi = $mevcutBakim ? null : $request->bakim_tarihi;
            $smsHatirlatma = $mevcutBakim ? false : ($request->has('sms_hatirlatma') ? true : false);
            
            // Servis bilgilerini güncelle
            $servis->update([
                'yapilan_islemler' => $request->yapilan_islemler,
                'toplam_mal_hizmet_tutari' => $request->toplam_mal_hizmet_tutari,
                'toplam_iskonto' => $request->toplam_iskonto ?? 0,
                'hesaplanan_kdv' => $request->hesaplanan_kdv ?? 0,
                'vergiler_dahil_toplam' => $request->vergiler_dahil_toplam,
                'servis_sonucu' => $request->servis_sonucu,
                'periyodik_bakim' => $periyodikBakim,
                'bakim_tarihi' => $bakimTarihi,
                'sms_hatirlatma' => $smsHatirlatma,
                'islem_garantisi' => $request->islem_garantisi,
                'odeme_yontemi' => $request->odeme_yontemi,
                'durum' => 'Tamamlandı',
                'tamamlanma_tarihi' => now(),
            ]);

            // Bu müşteriye ait "İşlemde" durumundaki bakımı "Tamamlandı" yap
            Servis::where('cari_hesap_id', $servis->cari_hesap_id)
                ->whereNotNull('periyodik_bakim')
                ->whereNotNull('bakim_tarihi')
                ->where('durum', 'İşlemde')
                ->update([
                    'durum' => 'Tamamlandı',
                    'tamamlanma_tarihi' => now()
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
        // Tüm bakım kayıtlarını getir (her müşteri için birden fazla olabilir)
        // Durum: null (planlandı), Bekliyor, İşlemde, Tamamlandı
        $query = Servis::with('cariHesap')
            ->whereNotNull('periyodik_bakim')
            ->whereNotNull('bakim_tarihi');

        // Servis durumu filtresi
        if ($request->filled('servis_durumu')) {
            $query->where('durum', $request->servis_durumu);
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

        // İstatistikler için tüm kayıtları kontrol et (durum filtresi eklenmeden önce)
        $today = now();
        $tumKayitlar = clone $query; // Query'i durum filtresi eklenmeden klonla
        
        $stats = [
            'gecmis' => 0,
            'yaklasan' => 0,
            'normal' => 0,
            'toplam' => $tumKayitlar->count()
        ];
        
        // Tüm kayıtlar için durum hesapla (chunk ile memory optimize)
        $tumKayitlar->chunk(500, function ($kayitlar) use (&$stats, $today) {
            foreach ($kayitlar as $kayit) {
                if ($kayit->bakim_tarihi) {
                    $gunFarki = $today->diffInDays($kayit->bakim_tarihi, false);
                    
                    if ($gunFarki < 0) {
                        $stats['gecmis']++;
                    } elseif ($gunFarki <= 7) {
                        $stats['yaklasan']++;
                    } else {
                        $stats['normal']++;
                    }
                }
            }
        });
        
        // Bakım durum filtresi (İstatistik kartlarından gelen) - İstatistikler hesaplandıktan sonra uygula
        $durumFilter = $request->input('durum_filter');
        if ($durumFilter && in_array($durumFilter, ['gecmis', 'yaklasan', 'normal'])) {
            $today = now();
            $weekLater = now()->addDays(7);
            
            if ($durumFilter === 'gecmis') {
                // Geçmiş bakımlar (tarihi geçmiş)
                $query->where('bakim_tarihi', '<', $today);
            } elseif ($durumFilter === 'yaklasan') {
                // Yaklaşan bakımlar (7 gün içinde)
                $query->whereBetween('bakim_tarihi', [$today, $weekLater]);
            } elseif ($durumFilter === 'normal') {
                // Normal bakımlar (7+ gün sonra)
                $query->where('bakim_tarihi', '>', $weekLater);
            }
        }
        
        // Sayfa başına kayıt sayısı (varsayılan: 50)
        $perPage = $request->input('per_page', 50);
        if (!in_array($perPage, [20, 50, 100, 200])) {
            $perPage = 50;
        }
        
        // Sıralama seçeneği (varsayılan: akıllı sıralama)
        $sortBy = $request->input('sort_by', 'smart');
        
        // Akıllı sıralama: Yaklaşan bakımlar önce, sonra normal, en sonda geçmişler
        if ($sortBy === 'smart') {
            $today = now()->format('Y-m-d H:i:s');
            $weekLater = now()->addDays(7)->format('Y-m-d H:i:s');
            
            $servisler = $query->orderByRaw("
                CASE 
                    WHEN bakim_tarihi BETWEEN ? AND ? THEN 1  -- Yaklaşan (7 gün içinde)
                    WHEN bakim_tarihi > ? THEN 2              -- Normal (7+ gün sonra)
                    ELSE 3                                     -- Geçmiş
                END
            ", [$today, $weekLater, $weekLater])
            ->orderBy('bakim_tarihi', 'asc')
            ->paginate($perPage);
        } elseif ($sortBy === 'date_asc') {
            // Tarihe göre artan (eskiden yeniye)
            $servisler = $query->orderBy('bakim_tarihi', 'asc')->paginate($perPage);
        } elseif ($sortBy === 'date_desc') {
            // Tarihe göre azalan (yeniden eskiye)
            $servisler = $query->orderBy('bakim_tarihi', 'desc')->paginate($perPage);
        } else {
            // Varsayılan
            $servisler = $query->orderBy('bakim_tarihi', 'asc')->paginate($perPage);
        }
        
        // Her servise "durum" ekle ve otomatik güncelle
        $servisler->getCollection()->transform(function ($servis) {
            $bakimTarihi = $servis->bakim_tarihi;
            $today = now();
            $weekLater = $today->copy()->addDays(7);
            
            if ($bakimTarihi) {
                $gunFarki = $today->diffInDays($bakimTarihi, false);
                $bakimTarihiCarbon = \Carbon\Carbon::parse($bakimTarihi);
                
                // Bu müşteriye ait açık (tamamlanmamış) servis var mı kontrol et
                $acikServisVar = Servis::where('cari_hesap_id', $servis->cari_hesap_id)
                    ->whereIn('durum', ['İşlemde', 'Beklemede'])
                    ->where('id', '!=', $servis->id)
                    ->whereNull('periyodik_bakim')
                    ->exists();
                
                // Durumu hesapla
                $yeniDurum = null;
                
                if ($servis->durum == 'Tamamlandı') {
                    // Tamamlanmış ise olduğu gibi kalsın
                    $yeniDurum = 'Tamamlandı';
                } elseif ($acikServisVar) {
                    // Açık servis varsa → İşlemde
                    $yeniDurum = 'İşlemde';
                } else {
                    // Açık servis yoksa → Bekliyor
                    $yeniDurum = 'Bekliyor';
                }
                
                // Eğer durum değiştiyse güncelle
                if ($servis->durum != $yeniDurum) {
                    $servis->update(['durum' => $yeniDurum]);
                    $servis->durum = $yeniDurum;
                }
                
                // Renklendirme için (tarihe göre aciliyet)
                // Bakım tarihi geldi mi kontrol et (7 gün içinde veya geçmiş)
                $bakimZamaniGeldi = $bakimTarihiCarbon->lessThanOrEqualTo($weekLater);
                
                if ($gunFarki < 0) {
                    $servis->bakim_durum = 'gecmis'; // Tarihi geçmiş (kırmızı)
                    $servis->aciliyet = 'yuksek'; // Acil!
                } elseif ($gunFarki <= 7) {
                    $servis->bakim_durum = 'yaklasan'; // Yaklaşan (sarı)
                    $servis->aciliyet = 'orta';
                } else {
                    $servis->bakim_durum = 'normal'; // Normal (yeşil)
                    $servis->aciliyet = 'dusuk';
                }
                
                $servis->gun_farki = round(abs($gunFarki));
            } else {
                $servis->bakim_durum = 'belirsiz';
                $servis->gun_farki = null;
                $servis->aciliyet = 'dusuk';
            }
            
            return $servis;
        });

        return view('bakim.index', compact('servisler', 'stats', 'durumFilter'));
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

            // Bakım durumu her zaman "Bekliyor" olarak başlar (servis açılmamış)
            $bakimDurumu = 'Bekliyor';

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
                'durum' => $bakimDurumu,
                'tamamlanma_tarihi' => null,
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

    // Bakım Detay Sayfası
    public function bakimShow($id)
    {
        $bakim = Servis::with('cariHesap')->findOrFail($id);
        
        // O müşterinin geçmiş servislerini getir (Tamamlanmış tüm servisler)
        $gecmisBakimlar = Servis::where('cari_hesap_id', $bakim->cari_hesap_id)
            ->where('durum', 'Tamamlandı')
            ->where('id', '!=', $id)
            ->orderBy('tamamlanma_tarihi', 'desc')
            ->get();
        
        // Mevcut bakımın durumunu hesapla
        $bakimTarihi = $bakim->bakim_tarihi;
        $today = now();
        
        if ($bakimTarihi) {
            $gunFarki = $today->diffInDays($bakimTarihi, false);
            
            if ($gunFarki < 0) {
                $bakim->bakim_durum = 'gecmis';
            } elseif ($gunFarki <= 7) {
                $bakim->bakim_durum = 'yaklasan';
            } else {
                $bakim->bakim_durum = 'normal';
            }
            
            $bakim->gun_farki = round(abs($gunFarki));
        } else {
            $bakim->bakim_durum = 'bekliyor';
            $bakim->gun_farki = null;
        }
        
        return view('bakim.show', compact('bakim', 'gecmisBakimlar'));
    }

    // Bakım Düzenleme Formu
    public function bakimEdit($id)
    {
        $bakim = Servis::with('cariHesap')->findOrFail($id);
        return view('bakim.edit', compact('bakim'));
    }

    // Bakım Güncelleme
    public function bakimUpdate(Request $request, $id)
    {
        $bakim = Servis::findOrFail($id);

        $request->validate([
            'marka' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'periyodik_bakim' => 'required|string',
            'bakim_tarihi' => 'nullable|date',
            'bakim_lokasyonu' => 'nullable|string|max:255',
            'bakim_icerigi' => 'nullable|string',
            'sms_hatirlatma' => 'nullable|boolean',
            'hatirlatma_zamani' => 'nullable|string',
        ]);

        // Eğer periyot değişmişse ve tarih girilmemişse, tarihi otomatik hesapla
        $bakimTarihi = $request->bakim_tarihi;
        
        if (!$bakimTarihi || $request->periyodik_bakim != $bakim->periyodik_bakim) {
            // Başlangıç tarihi olarak ilk bakım tarihi veya tamamlanma tarihini kullan
            $baslangicTarihi = $bakim->ilk_bakim_tarihi ?? $bakim->tamamlanma_tarihi ?? now();
            
            // Periyoda göre yeni tarihi hesapla
            $bakimTarihi = $this->calculateNextBakimTarihi($baslangicTarihi, $request->periyodik_bakim);
        }

        $bakim->update([
            'marka' => $request->marka,
            'model' => $request->model,
            'periyodik_bakim' => $request->periyodik_bakim,
            'bakim_tarihi' => $bakimTarihi,
            'bakim_lokasyonu' => $request->bakim_lokasyonu,
            'bakim_icerigi' => $request->bakim_icerigi,
            'sms_hatirlatma' => $request->has('sms_hatirlatma') ? true : false,
            'hatirlatma_zamani' => $request->hatirlatma_zamani,
        ]);

        return redirect()->route('bakim.show', $id)
            ->with('success', 'Bakım bilgileri başarıyla güncellendi! ' . 
                ($request->periyodik_bakim != $bakim->periyodik_bakim ? 
                'Bakım tarihi yeni periyoda göre güncellendi.' : ''));
    }

    // Yardımcı fonksiyon: Periyoda göre bakım tarihi hesapla
    private function calculateNextBakimTarihi($baslangicTarihi, $periyot)
    {
        $tarih = \Carbon\Carbon::parse($baslangicTarihi);
        
        // "X Aylık" formatını parse et (örn: "8 Aylık" -> 8)
        if (preg_match('/^(\d+)\s*Aylık/i', $periyot, $matches)) {
            $aylar = (int)$matches[1];
            return $tarih->addMonths($aylar);
        }
        
        // Özel durumlar
        switch ($periyot) {
            case 'Aylık':
                return $tarih->addMonth();
            case 'Yıllık':
                return $tarih->addYear();
            case '2 Yıllık':
                return $tarih->addYears(2);
            case 'Bir Kez':
            default:
                return $tarih;
        }
    }

    // Bakım Silme
    public function bakimDelete($id)
    {
        $bakim = Servis::findOrFail($id);
        
        // Bakım bilgilerini temizle (servisi silmek yerine)
        $bakim->update([
            'periyodik_bakim' => null,
            'bakim_tarihi' => null,
            'bakim_lokasyonu' => null,
            'bakim_icerigi' => null,
            'sms_hatirlatma' => false,
            'hatirlatma_zamani' => null,
            'ilk_bakim_tarihi' => null,
        ]);

        return redirect()->route('bakim.index')
            ->with('success', 'Bakım takibi başarıyla kaldırıldı!');
    }

    // Servise Dönüştür
    public function bakimConvertToService($id)
    {
        $bakim = Servis::with('cariHesap')->findOrFail($id);
        
        // Servis oluşturma sayfasına yönlendir, cari bilgisiyle
        return redirect()->route('servis.create', ['cari_id' => $bakim->cari_hesap_id])
            ->with('bakim_info', [
                'bakim_id' => $bakim->id, // Bakım ID'sini de gönder
                'marka' => $bakim->marka,
                'model' => $bakim->model,
                'bakim_notu' => 'Periyodik bakım: ' . $bakim->periyodik_bakim,
                'cari' => $bakim->cariHesap
            ]);
    }

    // Bakım İçe Aktarma
    public function bakimImport(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240'
        ]);

        $file = $request->file('file');
        $fileContent = file_get_contents($file->getRealPath());
        
        // UTF-8 encode - Windows-1254 (Türkçe) veya ISO-8859-9'dan dönüştür
        $encoding = mb_detect_encoding($fileContent, ['UTF-8', 'Windows-1254', 'ISO-8859-9', 'ISO-8859-1'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $fileContent = mb_convert_encoding($fileContent, 'UTF-8', $encoding);
        }
        
        // Satır sonlarını normalize et (Windows \r\n, Mac \r, Unix \n)
        $fileContent = str_replace(["\r\n", "\r"], "\n", $fileContent);
        
        $lines = explode("\n", $fileContent);
        
        // İlk satırdan delimiter'ı tespit et (virgül veya noktalı virgül)
        $firstLine = $lines[0] ?? '';
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        
        $header = str_getcsv(array_shift($lines), $delimiter);
        
        // BOM karakterini temizle
        if (!empty($header[0])) {
            $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
        }
        
        $imported = 0;
        $skipped = 0;
        $errors = [];
        $debug = []; // Debug için
        
        foreach ($lines as $lineNumber => $line) {
            if (empty(trim($line))) continue;
            
            $data = str_getcsv($line, $delimiter);
            
            // Debug: İlk 3 satırı logla
            if ($lineNumber < 3) {
                $debug[] = "Satır " . ($lineNumber + 1) . " - Data count: " . count($data) . ", Header count: " . count($header);
            }
            
            if (count($data) < count($header)) {
                $skipped++;
                $errors[] = "Satır " . ($lineNumber + 2) . ": Eksik kolon (Beklenen: " . count($header) . ", Gelen: " . count($data) . ")";
                continue;
            }
            
            $row = array_combine($header, $data);
            
            try {
                // Müşteri ünvanına göre cari hesap bul
                $musteriUnvani = trim($row['Müşteri Ünvanı'] ?? '');
                
                if (empty($musteriUnvani)) {
                    $skipped++;
                    $errors[] = "Satır " . ($lineNumber + 2) . ": Müşteri ünvanı boş";
                    continue;
                }
                
                // Önce tam eşleşme dene
                $cariHesap = CariHesap::where('cari_hesap_adi', $musteriUnvani)->first();
                
                // Bulamazsan kısmi eşleşme dene
                if (!$cariHesap) {
                    $cariHesap = CariHesap::where('cari_hesap_adi', 'LIKE', "%{$musteriUnvani}%")->first();
                }
                
                // Hala bulamazsan tersten dene (Excel'deki isim sistemdeki ismin bir parçası olabilir)
                if (!$cariHesap) {
                    $cariHesap = CariHesap::where('cari_hesap_adi', 'LIKE', "%{$musteriUnvani}%")
                        ->orWhere(function($query) use ($musteriUnvani) {
                            // İsimdeki fazladan boşlukları temizle ve tekrar dene
                            $cleanName = preg_replace('/\s+/', ' ', $musteriUnvani);
                            $query->where('cari_hesap_adi', $cleanName)
                                  ->orWhere('cari_hesap_adi', 'LIKE', "%{$cleanName}%");
                        })
                        ->first();
                }
                
                if (!$cariHesap) {
                    $skipped++;
                    $errors[] = "Satır " . ($lineNumber + 2) . ": Müşteri bulunamadı - '{$musteriUnvani}' (Sistemde kayıtlı değil)";
                    continue;
                }
                
                // Bakım tarihi parse et
                $bakimTarihi = null;
                if (!empty($row['Bakım Tarihi'])) {
                    try {
                        // ISO formatı (2024-07-09 veya 2024-07-09 13:39:00)
                        $bakimTarihi = \Carbon\Carbon::parse($row['Bakım Tarihi']);
                    } catch (\Exception $e) {
                        try {
                            // Türkçe tarih formatı (dd.mm.yyyy veya dd.mm.yyyy hh:mm:ss)
                            $tarihStr = trim($row['Bakım Tarihi']);
                            
                            // Saat varsa format: d.m.Y H:i:s
                            if (strpos($tarihStr, ':') !== false) {
                                $bakimTarihi = \Carbon\Carbon::createFromFormat('d.m.Y H:i:s', $tarihStr);
                            } else {
                                // Sadece tarih: d.m.Y
                                $bakimTarihi = \Carbon\Carbon::createFromFormat('d.m.Y', $tarihStr);
                            }
                        } catch (\Exception $e2) {
                            $errors[] = "Satır " . ($lineNumber + 2) . ": Geçersiz tarih formatı: {$row['Bakım Tarihi']}";
                        }
                    }
                }
                
                // Servis numarası oluştur
                $servisNo = Servis::generateServisNo();
                
                // Bakım periyodunu normalize et (küçük harf -> büyük harf)
                $bakimPeriyodu = $row['Bakım Periyodu'] ?? null;
                if ($bakimPeriyodu) {
                    $periyotLower = strtolower(trim($bakimPeriyodu));
                    
                    // "Bir kez" -> "Bir Kez" gibi düzeltmeler
                    $periyotMap = [
                        'bir kez' => 'Bir Kez',
                        'aylık' => '1 Aylık',
                        'yıllık' => '12 Aylık',
                    ];
                    
                    if (isset($periyotMap[$periyotLower])) {
                        $bakimPeriyodu = $periyotMap[$periyotLower];
                    } else {
                        // "8 aylık" -> "8 Aylık" gibi büyük harf düzeltmesi
                        if (preg_match('/^(\d+)\s*aylık$/i', $periyotLower, $matches)) {
                            $bakimPeriyodu = $matches[1] . ' Aylık';
                        }
                    }
                }
                
                // Eğer bakım periyodu varsa ve bakım tarihi yoksa, bir sonraki bakım tarihini hesapla
                if ($bakimPeriyodu && $bakimPeriyodu !== 'Bir Kez' && $bakimTarihi) {
                    $ilkBakimTarihi = $bakimTarihi;
                    $bakimTarihi = $this->calculateNextBakimTarihi($ilkBakimTarihi, $bakimPeriyodu);
                } else {
                    $ilkBakimTarihi = $bakimTarihi;
                }
                
                // Bakım durumu her zaman "Bekliyor" olarak başlar (servis açılmamış)
                $bakimDurumu = 'Bekliyor';
                
                // Bakım kaydı oluştur
                Servis::create([
                    'servis_no' => $servisNo,
                    'cari_hesap_id' => $cariHesap->id,
                    'marka' => $row['Marka'] ?? null,
                    'model' => $row['Model'] ?? null,
                    'periyodik_bakim' => $bakimPeriyodu,
                    'bakim_tarihi' => $bakimTarihi,
                    'bakim_icerigi' => $row['Bakım İçeriği'] ?? null,
                    'bakim_lokasyonu' => $row['Bakım Lokasyonu'] ?? null,
                    'personel' => $row['Personel'] ?? null,
                    'teknisyenin_yorumu' => $row['Bakım Notu'] ?? null,
                    'durum' => $bakimDurumu,
                    'ilk_bakim_tarihi' => $ilkBakimTarihi,
                    'tamamlanma_tarihi' => null,
                ]);
                
                $imported++;
                
            } catch (\Exception $e) {
                $skipped++;
                $errors[] = "Satır " . ($lineNumber + 2) . ": " . $e->getMessage();
            }
        }
        
        $message = "{$imported} bakım kaydı başarıyla içe aktarıldı.";
        if ($skipped > 0) {
            $message .= " {$skipped} kayıt atlandı.";
        }
        
        // Debug bilgisi ekle
        if (!empty($debug)) {
            $message .= " | Delimiter: " . ($delimiter === ';' ? 'noktalı virgül' : 'virgül');
            $message .= " | Kolon sayısı: " . count($header);
        }
        
        return redirect()->route('bakim.index')
            ->with('success', $message)
            ->with('import_errors', $errors)
            ->with('import_debug', $debug);
    }

    // PDF Yazdırma Metodları
    
    public function pdfTeslimFormu($id)
    {
        $servis = Servis::with('cariHesap')->findOrFail($id);
        $settings = \App\Models\Setting::getSettings();
        
        return view('servis.pdf.teslim-formu', compact('servis', 'settings'));
    }

    public function pdfKabulFormu($id)
    {
        $servis = Servis::with('cariHesap')->findOrFail($id);
        $settings = \App\Models\Setting::getSettings();
        
        return view('servis.pdf.kabul-formu', compact('servis', 'settings'));
    }

    public function pdfFis($id)
    {
        $servis = Servis::with('cariHesap', 'urunler')->findOrFail($id);
        $settings = \App\Models\Setting::getSettings();
        
        return view('servis.pdf.fis', compact('servis', 'settings'));
    }
}
