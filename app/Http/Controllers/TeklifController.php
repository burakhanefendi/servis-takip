<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teklif;
use App\Models\TeklifUrun;
use App\Models\CariHesap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeklifController extends Controller
{
    // Teklif listesi
    public function index()
    {
        $teklifler = Teklif::with('cariHesap')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('teklif.index', compact('teklifler'));
    }
    
    // Teklif oluşturma formu
    public function create()
    {
        $teklifNo = Teklif::generateTeklifNo();
        $cariHesaplar = CariHesap::orderBy('cari_hesap_adi')->get();
        
        return view('teklif.create', compact('teklifNo', 'cariHesaplar'));
    }
    
    // Teklif kaydetme
    public function store(Request $request)
    {
        $request->validate([
            'cari_hesap_id' => 'required|exists:cari_hesaplar,id',
            'teklif_baslik' => 'nullable|string|max:255',
            'baslangic_tarihi' => 'required|date',
            'gecerlilik_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
            'para_birimi' => 'required|in:TRY,USD,EUR,GBP',
        ]);
        
        try {
            DB::beginTransaction();
            
            $teklif = Teklif::create([
                'teklif_no' => Teklif::generateTeklifNo(),
                'cari_hesap_id' => $request->cari_hesap_id,
                'baslik' => $request->teklif_baslik,
                'baslangic_tarihi' => $request->baslangic_tarihi,
                'gecerlilik_tarihi' => $request->gecerlilik_tarihi,
                'para_birimi' => $request->para_birimi,
                'fotograflar_goster' => $request->has('fotograflar_goster'),
                'durum' => 'taslak',
                'notlar' => $request->notlar,
            ]);
            
            // Ürünleri ekle
            if ($request->has('products')) {
                $products = json_decode($request->products, true);
                foreach ($products as $index => $productData) {
                    $urun = new TeklifUrun();
                    $urun->teklif_id = $teklif->id;
                    $urun->sku = $productData['sku'] ?? '';
                    $urun->stok_tanimi = $productData['stok_tanimi'];
                    $urun->miktar = $productData['miktar'];
                    $urun->birim = $productData['birim'];
                    $urun->birim_fiyat = $productData['birim_fiyat'];
                    $urun->kdv_oran = $productData['kdv_oran'];
                    $urun->indirim_oran = $productData['indirim_oran'] ?? 0;
                    $urun->depo = $productData['depo'] ?? 'Varsayılan';
                    $urun->sira_no = $index + 1;
                    $urun->calculateAmounts(); // Bu metod zaten save() yapıyor
                }
                
                // Teklif toplamlarını güncelle
                $teklif->calculateTotals();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Teklif başarıyla oluşturuldu.',
                'teklif_id' => $teklif->id,
                'redirect' => route('teklif.show', $teklif->id)
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Teklif detayı
    public function show($id)
    {
        $teklif = Teklif::with(['cariHesap', 'urunler'])->findOrFail($id);
        return view('teklif.show', compact('teklif'));
    }
    
    // Teklif düzenleme formu
    public function edit($id)
    {
        $teklif = Teklif::with(['cariHesap', 'urunler'])->findOrFail($id);
        $cariHesaplar = CariHesap::orderBy('cari_hesap_adi')->get();
        
        return view('teklif.edit', compact('teklif', 'cariHesaplar'));
    }
    
    // Teklif güncelleme
    public function update(Request $request, $id)
    {
        $teklif = Teklif::findOrFail($id);
        
        $request->validate([
            'cari_hesap_id' => 'required|exists:cari_hesaplar,id',
            'teklif_baslik' => 'nullable|string|max:255',
            'baslangic_tarihi' => 'required|date',
            'gecerlilik_tarihi' => 'required|date|after_or_equal:baslangic_tarihi',
            'para_birimi' => 'required|in:TRY,USD,EUR,GBP',
        ]);
        
        try {
            DB::beginTransaction();
            
            $teklif->update([
                'cari_hesap_id' => $request->cari_hesap_id,
                'baslik' => $request->teklif_baslik,
                'baslangic_tarihi' => $request->baslangic_tarihi,
                'gecerlilik_tarihi' => $request->gecerlilik_tarihi,
                'para_birimi' => $request->para_birimi,
                'fotograflar_goster' => $request->has('fotograflar_goster'),
                'notlar' => $request->notlar,
            ]);
            
            // Eski ürünleri sil ve yenileri ekle
            if ($request->has('products')) {
                // Eski ürünleri sil
                TeklifUrun::where('teklif_id', $teklif->id)->delete();
                
                // Yeni ürünleri ekle
                $products = json_decode($request->products, true);
                foreach ($products as $index => $productData) {
                    $urun = new TeklifUrun();
                    $urun->teklif_id = $teklif->id;
                    $urun->sku = $productData['sku'] ?? '';
                    $urun->stok_tanimi = $productData['stok_tanimi'];
                    $urun->miktar = $productData['miktar'];
                    $urun->birim = $productData['birim'];
                    $urun->birim_fiyat = $productData['birim_fiyat'];
                    $urun->kdv_oran = $productData['kdv_oran'];
                    $urun->indirim_oran = $productData['indirim_oran'] ?? 0;
                    $urun->depo = $productData['depo'] ?? 'Varsayılan';
                    $urun->sira_no = $index + 1;
                    $urun->calculateAmounts(); // Bu metod zaten save() yapıyor
                }
                
                // Teklif toplamlarını güncelle
                $teklif->calculateTotals();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Teklif başarıyla güncellendi.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Teklif silme
    public function destroy($id)
    {
        try {
            $teklif = Teklif::findOrFail($id);
            $teklif->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Teklif başarıyla silindi.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Ürün ekleme (AJAX)
    public function addUrun(Request $request)
    {
        $request->validate([
            'teklif_id' => 'required|exists:teklifler,id',
            'stok_tanimi' => 'required|string',
            'miktar' => 'required|numeric|min:0',
            'birim' => 'required|string',
            'birim_fiyat' => 'required|numeric|min:0',
            'kdv_oran' => 'required|numeric|min:0|max:100',
            'indirim_oran' => 'nullable|numeric|min:0|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            $urun = new TeklifUrun();
            $urun->teklif_id = $request->teklif_id;
            $urun->sku = $request->sku;
            $urun->stok_tanimi = $request->stok_tanimi;
            $urun->miktar = $request->miktar;
            $urun->birim = $request->birim;
            $urun->birim_fiyat = $request->birim_fiyat;
            $urun->kdv_oran = $request->kdv_oran;
            $urun->indirim_oran = $request->indirim_oran ?? 0;
            $urun->depo = $request->depo;
            $urun->sira_no = TeklifUrun::where('teklif_id', $request->teklif_id)->max('sira_no') + 1;
            $urun->calculateAmounts();
            
            // Teklif toplamlarını güncelle
            $teklif = Teklif::find($request->teklif_id);
            $teklif->calculateTotals();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla eklendi.',
                'urun' => $urun
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Ürün güncelleme (AJAX)
    public function updateUrun(Request $request, $id)
    {
        $urun = TeklifUrun::findOrFail($id);
        
        $request->validate([
            'stok_tanimi' => 'required|string',
            'miktar' => 'required|numeric|min:0',
            'birim' => 'required|string',
            'birim_fiyat' => 'required|numeric|min:0',
            'kdv_oran' => 'required|numeric|min:0|max:100',
            'indirim_oran' => 'nullable|numeric|min:0|max:100',
        ]);
        
        try {
            DB::beginTransaction();
            
            $urun->sku = $request->sku;
            $urun->stok_tanimi = $request->stok_tanimi;
            $urun->miktar = $request->miktar;
            $urun->birim = $request->birim;
            $urun->birim_fiyat = $request->birim_fiyat;
            $urun->kdv_oran = $request->kdv_oran;
            $urun->indirim_oran = $request->indirim_oran ?? 0;
            $urun->depo = $request->depo;
            $urun->calculateAmounts();
            
            // Teklif toplamlarını güncelle
            $teklif = Teklif::find($urun->teklif_id);
            $teklif->calculateTotals();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla güncellendi.',
                'urun' => $urun
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Ürün silme (AJAX)
    public function deleteUrun($id)
    {
        try {
            DB::beginTransaction();
            
            $urun = TeklifUrun::findOrFail($id);
            $teklifId = $urun->teklif_id;
            $urun->delete();
            
            // Teklif toplamlarını güncelle
            $teklif = Teklif::find($teklifId);
            $teklif->calculateTotals();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Ürün başarıyla silindi.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hata: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // PDF Oluşturma
    public function generatePdf($id)
    {
        $teklif = Teklif::with(['cariHesap', 'urunler'])->findOrFail($id);
        $settings = \App\Models\Setting::getSettings();
        
        return view('teklif.pdf', compact('teklif', 'settings'));
    }
}
