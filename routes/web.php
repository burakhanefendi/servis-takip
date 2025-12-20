<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CariHesapController;
use App\Http\Controllers\CariGroupController;
use App\Http\Controllers\ServisController;

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
    
    // Bakım Listesi
    Route::get('/bakim-listesi', [ServisController::class, 'bakimListesi'])->name('bakim.index');
    Route::get('/bakim/create', [ServisController::class, 'bakimCreate'])->name('bakim.create');
    Route::post('/bakim', [ServisController::class, 'bakimStore'])->name('bakim.store');
    Route::get('/bakim/{id}', [ServisController::class, 'bakimShow'])->name('bakim.show');
    
    // API - Cari Arama
    Route::get('/api/cari/search', [ServisController::class, 'searchCari'])->name('api.cari.search');
});
