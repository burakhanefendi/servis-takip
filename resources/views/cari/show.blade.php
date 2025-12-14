<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Detay - {{ $cari->cari_hesap_adi }}</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <style>
        .detail-section {
            background: white;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .detail-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .yetkili-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">☰</button>
            <h1>💧 Servis Takip Sistemi</h1>
        </div>
        <div class="header-right">
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Kullanıcı' }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Çıkış Yap</button>
            </form>
        </div>
    </header>

    <div class="layout">
        @include('layouts.sidebar')

        <main class="main-content">
            <div class="content-header">
                <h2>👤 Cari Detay</h2>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('cari.edit', $cari->id) }}" class="btn-new" style="background: #FF9800;">✏️ Düzenle</a>
                    <a href="{{ route('cari.index') }}" class="btn-new" style="background: #6c757d;">⬅ Geri Dön</a>
                </div>
            </div>

            <div class="detail-section">
                <h3 class="section-title">🏢 Firma Bilgileri</h3>
                <div class="detail-row"><div class="detail-label">Müşteri Kodu:</div><div class="detail-value"><strong>{{ $cari->musteri_kodu }}</strong></div></div>
                <div class="detail-row"><div class="detail-label">Cari Hesap Adı:</div><div class="detail-value">{{ $cari->cari_hesap_adi }}</div></div>
                <div class="detail-row"><div class="detail-label">Kısa İsim:</div><div class="detail-value">{{ $cari->kisa_isim ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Cari Grubu:</div><div class="detail-value">{{ $cari->cariGroup->name ?? '-' }}</div></div>
            </div>

            <div class="detail-section">
                <h3 class="section-title">📄 Vergi Bilgileri</h3>
                <div class="detail-row"><div class="detail-label">Vergi Dairesi:</div><div class="detail-value">{{ $cari->vergi_dairesi ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Vergi/TC No:</div><div class="detail-value">{{ $cari->vergi_kimlik_no ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">İBAN:</div><div class="detail-value">{{ $cari->iban ?: '-' }}</div></div>
            </div>

            <div class="detail-section">
                <h3 class="section-title">📍 Adres Bilgileri</h3>
                <div class="detail-row"><div class="detail-label">İl:</div><div class="detail-value">{{ $cari->il ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">İlçe:</div><div class="detail-value">{{ $cari->ilce ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Adres:</div><div class="detail-value">{{ $cari->adres ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Sevk Adresi:</div><div class="detail-value">{{ $cari->sevk_adresi ?: '-' }}</div></div>
            </div>

            <div class="detail-section">
                <h3 class="section-title">📞 İletişim Bilgileri</h3>
                <div class="detail-row"><div class="detail-label">GSM:</div><div class="detail-value">{{ $cari->gsm ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">E-posta:</div><div class="detail-value">{{ $cari->eposta ?: '-' }}</div></div>
                <div class="detail-row"><div class="detail-label">Sabit Telefon:</div><div class="detail-value">{{ $cari->sabit_telefon ?: '-' }}</div></div>
            </div>

            @if($cari->yetkiliKisiler->count() > 0)
            <div class="detail-section">
                <h3 class="section-title">👥 Yetkili Kişiler</h3>
                @foreach($cari->yetkiliKisiler as $yetkili)
                <div class="yetkili-card">
                    <strong>{{ $yetkili->ad_soyad }}</strong>
                    @if($yetkili->unvan) - <span style="color: #667eea;">{{ $yetkili->unvan }}</span>@endif
                    <div style="margin-top: 8px; font-size: 14px; color: #666;">
                        @if($yetkili->telefon) 📱 {{ $yetkili->telefon }} @endif
                        @if($yetkili->eposta) | 📧 {{ $yetkili->eposta }} @endif
                        @if($yetkili->dahili) | ☎️ Dahili: {{ $yetkili->dahili }} @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#menuToggle').on('click', function() { $('#sidebar').toggleClass('active'); $('#sidebarOverlay').toggleClass('active'); });
            $('#sidebarOverlay').on('click', function() { $('#sidebar').removeClass('active'); $('#sidebarOverlay').removeClass('active'); });
            $('.menu-item.has-submenu').on('click', function(e) {
                e.preventDefault();
                const submenuId = $(this).data('submenu');
                const $submenu = $('#submenu-' + submenuId);
                $('.submenu').not($submenu).removeClass('open');
                $('.menu-item.has-submenu').not(this).removeClass('open');
                $(this).toggleClass('open');
                $submenu.toggleClass('open');
            });
        });
    </script>
</body>
</html>

