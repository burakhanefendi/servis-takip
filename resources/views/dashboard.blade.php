@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="welcome-box">
    <div class="welcome-icon">👋</div>
    <h2>Hoş Geldiniz, {{ Auth::user()->name ?? 'Kullanıcı' }}!</h2>
    <p>Servis Takip Sistemine başarıyla giriş yaptınız.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-title">Toplam Müşteri</div>
        <div class="stat-value">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🔧</div>
        <div class="stat-title">Bekleyen Bakım</div>
        <div class="stat-value">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-title">Stok Ürün</div>
        <div class="stat-value">0</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📱</div>
        <div class="stat-title">Gönderilen SMS</div>
        <div class="stat-value">0</div>
    </div>
</div>
@endsection

