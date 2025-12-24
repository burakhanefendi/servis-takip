<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CariHesapController;
use App\Http\Controllers\CariGroupController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TeklifController;

// Ana sayfa - login'e yönlendir
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard ve Cari İşlemleri (giriş yapılmış kullanıcılar için)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Cari Hesaplar
    Route::get('/cari', [CariHesapController::class, 'index'])->name('cari.index');
    Route::get('/cari/create', [CariHesapController::class, 'create'])->name('cari.create');
    Route::post('/cari', [CariHesapController::class, 'store'])->name('cari.store');
    Route::get('/cari/{id}', [CariHesapController::class, 'show'])->name('cari.show');
    Route::get('/cari/{id}/edit', [CariHesapController::class, 'edit'])->name('cari.edit');
    Route::put('/cari/{id}', [CariHesapController::class, 'update'])->name('cari.update');
    Route::post('/cari/import', [CariHesapController::class, 'import'])->name('cari.import');
    
    // Cari Grupları
    Route::get('/cari-groups', [CariGroupController::class, 'index'])->name('cari.groups.index');
    Route::post('/cari-groups', [CariGroupController::class, 'store'])->name('cari.groups.store');
    Route::delete('/cari-groups/{id}', [CariGroupController::class, 'destroy'])->name('cari.groups.destroy');
    
    // Servisler
    Route::get('/servis', [ServisController::class, 'index'])->name('servis.index');
    Route::get('/servis/create', [ServisController::class, 'create'])->name('servis.create');
    Route::post('/servis', [ServisController::class, 'store'])->name('servis.store');
    Route::get('/servis/{id}', [ServisController::class, 'show'])->name('servis.show');
    Route::get('/servis/{id}/complete', [ServisController::class, 'complete'])->name('servis.complete');
    Route::post('/servis/{id}/finish', [ServisController::class, 'finish'])->name('servis.finish');
    
    // Servis PDF Yazdırma
    Route::get('/servis/{id}/pdf/teslim-formu', [ServisController::class, 'pdfTeslimFormu'])->name('servis.pdf.teslim');
    Route::get('/servis/{id}/pdf/kabul-formu', [ServisController::class, 'pdfKabulFormu'])->name('servis.pdf.kabul');
    Route::get('/servis/{id}/pdf/fis', [ServisController::class, 'pdfFis'])->name('servis.pdf.fis');
    
    // Bakım Listesi
    Route::get('/bakim-listesi', [ServisController::class, 'bakimListesi'])->name('bakim.index');
    Route::get('/bakim/create', [ServisController::class, 'bakimCreate'])->name('bakim.create');
    Route::post('/bakim', [ServisController::class, 'bakimStore'])->name('bakim.store');
    Route::get('/bakim/{id}', [ServisController::class, 'bakimShow'])->name('bakim.show');
    Route::get('/bakim/{id}/edit', [ServisController::class, 'bakimEdit'])->name('bakim.edit');
    Route::put('/bakim/{id}', [ServisController::class, 'bakimUpdate'])->name('bakim.update');
    Route::delete('/bakim/{id}', [ServisController::class, 'bakimDelete'])->name('bakim.delete');
    Route::get('/bakim/{id}/convert-to-service', [ServisController::class, 'bakimConvertToService'])->name('bakim.convert');
    Route::post('/bakim/import', [ServisController::class, 'bakimImport'])->name('bakim.import');
    
    // API - Cari Arama
    Route::get('/api/cari/search', [ServisController::class, 'searchCari'])->name('api.cari.search');
    
    // Teklifler
    Route::get('/teklif', [TeklifController::class, 'index'])->name('teklif.index');
    Route::get('/teklif/create', [TeklifController::class, 'create'])->name('teklif.create');
    Route::post('/teklif', [TeklifController::class, 'store'])->name('teklif.store');
    Route::get('/teklif/{id}', [TeklifController::class, 'show'])->name('teklif.show');
    Route::get('/teklif/{id}/edit', [TeklifController::class, 'edit'])->name('teklif.edit');
    Route::put('/teklif/{id}', [TeklifController::class, 'update'])->name('teklif.update');
    Route::delete('/teklif/{id}', [TeklifController::class, 'destroy'])->name('teklif.destroy');
    Route::get('/teklif/{id}/pdf', [TeklifController::class, 'generatePdf'])->name('teklif.pdf');
    
    // Teklif Ürünleri
    Route::post('/teklif/urun', [TeklifController::class, 'addUrun'])->name('teklif.urun.add');
    Route::put('/teklif/urun/{id}', [TeklifController::class, 'updateUrun'])->name('teklif.urun.update');
    Route::delete('/teklif/urun/{id}', [TeklifController::class, 'deleteUrun'])->name('teklif.urun.delete');
    
    // Ayarlar
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    
    // Tenant Yönetimi (Sadece Ana Domain - Admin)
    Route::prefix('admin/tenants')->name('admin.tenants.')
        ->middleware(\App\Http\Middleware\AdminOnly::class)
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\TenantAdminController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\TenantAdminController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\TenantAdminController::class, 'store'])->name('store');
            Route::delete('/{id}', [\App\Http\Controllers\TenantAdminController::class, 'destroy'])->name('destroy');
        });
});
