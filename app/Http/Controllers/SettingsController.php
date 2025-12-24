<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        
        // Eğer settings yoksa boş bir nesne oluştur
        if (!$settings) {
            $settings = new Setting();
        }
        
        return view('settings.index', compact('settings'));
    }
    
    public function update(Request $request)
    {
        try {
            // Validation - Logo'yu ayrı validate et
            $request->validate([
                'firma_adi' => 'required|string|max:255',
                'telefon_1' => 'nullable|string|max:20',
                'telefon_2' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'website' => 'nullable|string|max:255',
                'adres' => 'nullable|string',
                'il' => 'nullable|string|max:100',
                'ilce' => 'nullable|string|max:100',
            ]);
            
            // Logo varsa validate et
            if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
                $request->validate([
                    'logo' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
                ]);
            }
            
            $settings = Setting::first();
            
            if (!$settings) {
                $settings = new Setting();
            }
            
            $settings->firma_adi = $request->firma_adi;
            $settings->telefon_1 = $request->telefon_1;
            $settings->telefon_2 = $request->telefon_2;
            $settings->email = $request->email;
            $settings->website = $request->website;
            $settings->adres = $request->adres;
            $settings->il = $request->il;
            $settings->ilce = $request->ilce;
            
            // Logo upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                
                // Debug
                \Log::info('Logo yükleniyor:', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType()
                ]);
                
                // Eski logoyu sil
                if ($settings->logo && Storage::exists('public/' . $settings->logo)) {
                    Storage::delete('public/' . $settings->logo);
                }
                
                $logoPath = $file->store('logos', 'public');
                $settings->logo = $logoPath;
                
                \Log::info('Logo kaydedildi:', ['path' => $logoPath]);
            } else {
                \Log::info('Logo dosyası yüklenmedi');
            }
            
            $settings->save();
            
            return redirect()->route('settings.index')
                ->with('success', 'Ayarlar başarıyla güncellendi!');
                
        } catch (\Exception $e) {
            \Log::error('Ayarlar kaydedilirken hata:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->route('settings.index')
                ->with('error', 'Hata: ' . $e->getMessage());
        }
    }
}
