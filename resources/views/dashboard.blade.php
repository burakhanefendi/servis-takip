<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Servis Takip - Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mobil menü toggle
            $('#menuToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
            });

            // Overlay'e tıklayınca menüyü kapat
            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
            });

            // Submenu toggle
            $('.menu-item.has-submenu').on('click', function(e) {
                e.preventDefault();
                const submenuId = $(this).data('submenu');
                const $submenu = $('#submenu-' + submenuId);
                
                // Diğer submenüleri kapat
                $('.submenu').not($submenu).removeClass('open');
                $('.menu-item.has-submenu').not(this).removeClass('open');
                
                // Bu submenuyu aç/kapat
                $(this).toggleClass('open');
                $submenu.toggleClass('open');
            });

            // Menü itemlerine tıklayınca aktif yap
            $('.menu-item:not(.has-submenu)').on('click', function(e) {
                // Yakında olanlar çalışmasın
                if ($(this).find('.coming-soon-badge').length > 0) {
                    e.preventDefault();
                    return;
                }

                $('.menu-item').removeClass('active');
                $('.submenu-item').removeClass('active');
                $(this).addClass('active');

                // Mobilde menüyü kapat
                if ($(window).width() <= 768) {
                    $('#sidebar').removeClass('active');
                    $('#sidebarOverlay').removeClass('active');
                }
            });

            // Submenu itemlerine tıklayınca aktif yap
            $('.submenu-item').on('click', function(e) {
                // Yakında olanlar çalışmasın
                if ($(this).find('.coming-soon-badge').length > 0) {
                    e.preventDefault();
                    return;
                }

                $('.menu-item').removeClass('active');
                $('.submenu-item').removeClass('active');
                $(this).addClass('active');

                // Mobilde menüyü kapat
                if ($(window).width() <= 768) {
                    $('#sidebar').removeClass('active');
                    $('#sidebarOverlay').removeClass('active');
                }
            });
        });
    </script>
</body>
</html>

