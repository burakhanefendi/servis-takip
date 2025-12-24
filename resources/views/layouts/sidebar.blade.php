<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3><i class="fas fa-bars"></i> Menü</h3>
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('dashboard') }}" class="menu-item">
            <span class="menu-icon"><i class="fas fa-home"></i></span>
            <span class="menu-title">Ana Sayfa</span>
        </a>
        
        <!-- Cari Hesaplar -->
        <div class="menu-item has-submenu {{ Request::is('cari*') ? 'open' : '' }}" data-submenu="cari">
            <span class="menu-icon"><i class="fas fa-users"></i></span>
            <span class="menu-title">Cari Hesaplar</span>
            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
        </div>
        <div class="submenu {{ Request::is('cari*') ? 'open' : '' }}" id="submenu-cari">
            <a href="{{ route('cari.create') }}" class="submenu-item {{ Request::is('cari/create') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-plus"></i></span>
                <span>Cari Ekle</span>
            </a>
            <a href="{{ route('cari.index') }}" class="submenu-item {{ Request::is('cari') && !Request::is('cari/create') && !Request::is('cari-groups') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-list"></i></span>
                <span>Cari Listesi</span>
            </a>
            <a href="{{ route('cari.groups.index') }}" class="submenu-item {{ Request::is('cari-groups') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-folder"></i></span>
                <span>Cari Grupları</span>
            </a>
        </div>

        <!-- Servisler -->
        <div class="menu-item has-submenu {{ Request::is('servis*') || Request::is('bakim*') ? 'open' : '' }}" data-submenu="servis">
            <span class="menu-icon"><i class="fas fa-tools"></i></span>
            <span class="menu-title">Servisler</span>
            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
        </div>
        <div class="submenu {{ Request::is('servis*') || Request::is('bakim*') ? 'open' : '' }}" id="submenu-servis">
            <a href="{{ route('servis.create') }}" class="submenu-item {{ Request::is('servis/create') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-plus"></i></span>
                <span>Servis Ekle</span>
            </a>
            <a href="{{ route('servis.index') }}" class="submenu-item {{ Request::is('servis') && !Request::is('servis/create') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-chart-bar"></i></span>
                <span>Servis Durumu</span>
            </a>
            <a href="#" class="submenu-item">
                <span class="submenu-icon"><i class="fas fa-shield-alt"></i></span>
                <span>Garantili İşlemler</span>
                <span class="coming-soon-badge">Yakında</span>
            </a>
            <a href="{{ route('bakim.create') }}" class="submenu-item {{ Request::is('bakim/create') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-plus-circle"></i></span>
                <span>Bakım Ekle</span>
            </a>
            <a href="{{ route('bakim.index') }}" class="submenu-item {{ Request::is('bakim-listesi') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-clipboard-list"></i></span>
                <span>Bakım Listesi</span>
            </a>
            <a href="#" class="submenu-item">
                <span class="submenu-icon"><i class="fas fa-file-alt"></i></span>
                <span>İş Emirleri</span>
                <span class="coming-soon-badge">Yakında</span>
            </a>
        </div>
        <a href="#" class="menu-item">
            <span class="menu-icon"><i class="fas fa-boxes"></i></span>
            <span class="menu-title">Stok Yönetimi</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        
        <!-- Teklifler -->
        <div class="menu-item has-submenu {{ Request::is('teklif*') ? 'open' : '' }}" data-submenu="teklif">
            <span class="menu-icon"><i class="fas fa-file-invoice"></i></span>
            <span class="menu-title">Teklifler</span>
            <span class="menu-arrow"><i class="fas fa-chevron-right"></i></span>
        </div>
        <div class="submenu {{ Request::is('teklif*') ? 'open' : '' }}" id="submenu-teklif">
            <a href="{{ route('teklif.create') }}" class="submenu-item {{ Request::is('teklif/create') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-plus"></i></span>
                <span>Teklif Oluştur</span>
            </a>
            <a href="{{ route('teklif.index') }}" class="submenu-item {{ Request::is('teklif') && !Request::is('teklif/create') ? 'active' : '' }}">
                <span class="submenu-icon"><i class="fas fa-list"></i></span>
                <span>Teklif Listesi</span>
            </a>
        </div>
        <a href="#" class="menu-item">
            <span class="menu-icon"><i class="fas fa-sms"></i></span>
            <span class="menu-title">SMS Gönder</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        <a href="#" class="menu-item">
            <span class="menu-icon"><i class="fas fa-list-alt"></i></span>
            <span class="menu-title">SMS Listesi</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        <a href="{{ route('profile.index') }}" class="menu-item {{ Request::is('profile*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fas fa-user-circle"></i></span>
            <span class="menu-title">Profil</span>
        </a>
        <a href="{{ route('settings.index') }}" class="menu-item {{ Request::is('settings*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="fas fa-cog"></i></span>
            <span class="menu-title">Sistem Ayarları</span>
        </a>
    </nav>
</aside>

<!-- Sidebar Overlay (Mobil için) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

