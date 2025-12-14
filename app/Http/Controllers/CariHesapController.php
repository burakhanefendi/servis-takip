<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CariHesap;
use App\Models\CariGroup;
use App\Models\YetkiliKisi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CariHesapController extends Controller
{
    // Cari ekle sayfasını göster
    public function create()
    {
        $cariGroups = CariGroup::all();
        $musteriKodu = CariHesap::generateMusteriKodu();
        return view('cari.create', compact('cariGroups', 'musteriKodu'));
    }

    // Cari kaydet
    public function store(Request $request)
    {
        $request->validate([
            'cari_hesap_adi' => 'required|string|max:255',
            'musteri_kodu' => 'required|unique:cari_hesaplar,musteri_kodu',
        ]);

        DB::beginTransaction();
        try {
            // Cari hesap oluştur
            $cariHesap = CariHesap::create([
                'cari_hesap_adi' => $request->cari_hesap_adi,
                'musteri_kodu' => $request->musteri_kodu,
                'kisa_isim' => $request->kisa_isim,
                'cari_group_id' => $request->cari_group_id,
                'vergi_dairesi' => $request->vergi_dairesi,
                'vergi_kimlik_no' => $request->vergi_kimlik_no,
                'iban' => $request->iban,
                'il' => $request->il,
                'ilce' => $request->ilce,
                'adres' => $request->adres,
                'sevk_adresi' => $request->sevk_adresi,
                'gsm' => $request->gsm,
                'eposta' => $request->eposta,
                'sabit_telefon' => $request->sabit_telefon,
            ]);

            // Yetkili kişileri kaydet (eğer varsa)
            if ($request->has('yetkili_kisiler')) {
                foreach ($request->yetkili_kisiler as $yetkili) {
                    if (!empty($yetkili['ad_soyad'])) {
                        YetkiliKisi::create([
                            'cari_hesap_id' => $cariHesap->id,
                            'ad_soyad' => $yetkili['ad_soyad'],
                            'unvan' => $yetkili['unvan'] ?? null,
                            'telefon' => $yetkili['telefon'] ?? null,
                            'eposta' => $yetkili['eposta'] ?? null,
                            'dahili' => $yetkili['dahili'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cari hesap başarıyla oluşturuldu!',
                'redirect' => route('cari.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cari listesi
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        
        $query = CariHesap::with('cariGroup');
        
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('cari_hesap_adi', 'LIKE', "%{$search}%")
                  ->orWhere('musteri_kodu', 'LIKE', "%{$search}%")
                  ->orWhere('gsm', 'LIKE', "%{$search}%")
                  ->orWhere('eposta', 'LIKE', "%{$search}%")
                  ->orWhere('il', 'LIKE', "%{$search}%");
            });
        }
        
        $cariHesaplar = $query->latest()->paginate(15);
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('cari.partials.table', compact('cariHesaplar'))->render(),
                'pagination' => $cariHesaplar->links('cari.partials.pagination')->render()
            ]);
        }
        
        return view('cari.index', compact('cariHesaplar'));
    }

    // Cari detay
    public function show($id)
    {
        $cari = CariHesap::with(['cariGroup', 'yetkiliKisiler'])->findOrFail($id);
        return view('cari.show', compact('cari'));
    }

    // Cari düzenle sayfası
    public function edit($id)
    {
        $cari = CariHesap::with('yetkiliKisiler')->findOrFail($id);
        $cariGroups = CariGroup::all();
        return view('cari.edit', compact('cari', 'cariGroups'));
    }

    // Cari güncelle
    public function update(Request $request, $id)
    {
        $request->validate([
            'cari_hesap_adi' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $cariHesap = CariHesap::findOrFail($id);
            
            $cariHesap->update([
                'cari_hesap_adi' => $request->cari_hesap_adi,
                'kisa_isim' => $request->kisa_isim,
                'cari_group_id' => $request->cari_group_id,
                'vergi_dairesi' => $request->vergi_dairesi,
                'vergi_kimlik_no' => $request->vergi_kimlik_no,
                'iban' => $request->iban,
                'il' => $request->il,
                'ilce' => $request->ilce,
                'adres' => $request->adres,
                'sevk_adresi' => $request->sevk_adresi,
                'gsm' => $request->gsm,
                'eposta' => $request->eposta,
                'sabit_telefon' => $request->sabit_telefon,
            ]);

            // Mevcut yetkili kişileri sil
            $cariHesap->yetkiliKisiler()->delete();

            // Yeni yetkili kişileri ekle
            if ($request->has('yetkili_kisiler')) {
                foreach ($request->yetkili_kisiler as $yetkili) {
                    if (!empty($yetkili['ad_soyad'])) {
                        YetkiliKisi::create([
                            'cari_hesap_id' => $cariHesap->id,
                            'ad_soyad' => $yetkili['ad_soyad'],
                            'unvan' => $yetkili['unvan'] ?? null,
                            'telefon' => $yetkili['telefon'] ?? null,
                            'eposta' => $yetkili['eposta'] ?? null,
                            'dahili' => $yetkili['dahili'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cari hesap başarıyla güncellendi!',
                'redirect' => route('cari.index')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }

    // Excel'den içe aktar
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Excel dosyalarını CSV'ye dönüştür
            if (in_array($extension, ['xlsx', 'xls'])) {
                $excel = \Excel::load($file->getRealPath())->get();
                $rows = $excel->toArray();
            } else {
                // CSV oku
                $handle = fopen($file->getRealPath(), 'r');
                $rows = [];
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $rows[] = $data;
                }
                fclose($handle);
            }

            // İlk satırı atla (başlıklar)
            array_shift($rows);

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                try {
                    // Boş satırları atla
                    if (empty($row[0]) && empty($row[2])) {
                        continue;
                    }

                    // Grup bulma veya oluşturma
                    $grupAdi = trim($row[1] ?? '');
                    $cariGroup = null;
                    if (!empty($grupAdi)) {
                        $cariGroup = CariGroup::firstOrCreate(
                            ['name' => $grupAdi],
                            ['description' => '']
                        );
                    }

                    // Müşteri kodu kontrolü
                    $musteriKodu = trim($row[0] ?? '');
                    if (empty($musteriKodu)) {
                        $musteriKodu = CariHesap::generateMusteriKodu();
                    }

                    // Var olan kaydı kontrol et
                    $existing = CariHesap::where('musteri_kodu', $musteriKodu)->first();
                    if ($existing) {
                        continue; // Zaten var, atla
                    }

                    // Cari oluştur
                    CariHesap::create([
                        'musteri_kodu' => $musteriKodu,
                        'cari_hesap_adi' => trim($row[2] ?? ''),
                        'cari_group_id' => $cariGroup ? $cariGroup->id : null,
                        'eposta' => trim($row[3] ?? ''),
                        'sabit_telefon' => trim($row[4] ?? ''),
                        'gsm' => trim($row[5] ?? ''),
                        'adres' => trim($row[6] ?? ''),
                        'il' => trim($row[7] ?? ''),
                        'ilce' => trim($row[8] ?? ''),
                        'vergi_dairesi' => trim($row[9] ?? ''),
                        'vergi_kimlik_no' => trim($row[10] ?? ''),
                        'iban' => trim($row[11] ?? ''),
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Satır " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            DB::commit();

            $message = "$imported kayıt başarıyla içe aktarıldı.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " hatalı kayıt atlandı.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Dosya işlenirken hata oluştu: ' . $e->getMessage()
            ], 500);
        }
    }
}
