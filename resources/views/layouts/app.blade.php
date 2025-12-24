<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Servis Takip Sistemi')</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="sidebarToggle"><i class="fas fa-bars"></i></button>
            <h1><i class="fas fa-tools"></i> Servis Takip Sistemi</h1>
        </div>
        <div class="header-right">
            <a href="{{ route('profile.index') }}" class="user-info-link">
                <div class="user-info">
                    <div class="user-name">{{ Auth::user()->name ?? 'Kullanıcı' }}</div>
                    <div class="user-email">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Çıkış Yap</button>
            </form>
        </div>
    </header>

    @include('layouts.sidebar')
    
    <div class="main-content">
        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Sidebar toggle - hem #menuToggle hem #sidebarToggle
            $('#sidebarToggle, #menuToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
            });

            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
            });

            $('.menu-item.has-submenu').on('click', function(e) {
                e.preventDefault();
                const submenuId = $(this).data('submenu');
                const $submenu = $('#submenu-' + submenuId);
                
                $('.submenu').not($submenu).removeClass('open');
                $('.menu-item.has-submenu').not(this).removeClass('open');
                
                $(this).toggleClass('open');
                $submenu.toggleClass('open');
            });

            // Menü itemlerine tıklayınca mobilde menüyü kapat
            $('.menu-item:not(.has-submenu), .submenu-item').on('click', function(e) {
                // Yakında olanlar çalışmasın
                if ($(this).find('.coming-soon-badge').length > 0) {
                    e.preventDefault();
                    return;
                }

                // Mobilde menüyü kapat
                if ($(window).width() <= 768) {
                    $('#sidebar').removeClass('active');
                    $('#sidebarOverlay').removeClass('active');
                }
            });

        });
    </script>
    
    @stack('scripts')
</body>
</html>

