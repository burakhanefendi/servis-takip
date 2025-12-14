<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>📋 Menü</h3>
    </div>
    <nav class="sidebar-menu">
        <a href="{{ route('dashboard') }}" class="menu-item">
            <span class="menu-icon">🏠</span>
            <span class="menu-title">Ana Sayfa</span>
        </a>
        
        <!-- Cari Hesaplar -->
        <div class="menu-item has-submenu {{ Request::is('cari*') ? 'open' : '' }}" data-submenu="cari">
            <span class="menu-icon">👥</span>
            <span class="menu-title">Cari Hesaplar</span>
            <span class="menu-arrow">▶</span>
        </div>
        <div class="submenu {{ Request::is('cari*') ? 'open' : '' }}" id="submenu-cari">
            <a href="{{ route('cari.create') }}" class="submenu-item {{ Request::is('cari/create') ? 'active' : '' }}">
                <span class="submenu-icon">➕</span>
                <span>Cari Ekle</span>
            </a>
            <a href="{{ route('cari.index') }}" class="submenu-item {{ Request::is('cari') && !Request::is('cari/create') && !Request::is('cari-groups') ? 'active' : '' }}">
                <span class="submenu-icon">📋</span>
                <span>Cari Listesi</span>
            </a>
            <a href="{{ route('cari.groups.index') }}" class="submenu-item {{ Request::is('cari-groups') ? 'active' : '' }}">
                <span class="submenu-icon">📁</span>
                <span>Cari Grupları</span>
            </a>
        </div>

        <!-- Servisler -->
        <div class="menu-item has-submenu {{ Request::is('servis*') ? 'open' : '' }}" data-submenu="servis">
            <span class="menu-icon">🔧</span>
            <span class="menu-title">Servisler</span>
            <span class="menu-arrow">▶</span>
        </div>
        <div class="submenu {{ Request::is('servis*') ? 'open' : '' }}" id="submenu-servis">
            <a href="{{ route('servis.create') }}" class="submenu-item {{ Request::is('servis/create') ? 'active' : '' }}">
                <span class="submenu-icon">➕</span>
                <span>Servis Ekle</span>
            </a>
            <a href="{{ route('servis.index') }}" class="submenu-item {{ Request::is('servis') && !Request::is('servis/create') ? 'active' : '' }}">
                <span class="submenu-icon">📊</span>
                <span>Servis Durumu</span>
            </a>
            <a href="#" class="submenu-item">
                <span class="submenu-icon">✅</span>
                <span>Garantili İşlemler</span>
                <span class="coming-soon-badge">Yakında</span>
            </a>
            <a href="#" class="submenu-item">
                <span class="submenu-icon">📋</span>
                <span>Bakım Listesi</span>
                <span class="coming-soon-badge">Yakında</span>
            </a>
            <a href="#" class="submenu-item">
                <span class="submenu-icon">📄</span>
                <span>İş Emirleri</span>
                <span class="coming-soon-badge">Yakında</span>
            </a>
        </div>
        <a href="#" class="menu-item">
            <span class="menu-icon">📦</span>
            <span class="menu-title">Stok Yönetimi</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        <a href="#" class="menu-item">
            <span class="menu-icon">📄</span>
            <span class="menu-title">PDF Teklif</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        <a href="#" class="menu-item">
            <span class="menu-icon">📱</span>
            <span class="menu-title">SMS Gönder</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        <a href="#" class="menu-item">
            <span class="menu-icon">📊</span>
            <span class="menu-title">SMS Listesi</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
        <a href="#" class="menu-item">
            <span class="menu-icon">⚙️</span>
            <span class="menu-title">Ayarlar</span>
            <span class="coming-soon-badge">Yakında</span>
        </a>
    </nav>
</aside>

<!-- Sidebar Overlay (Mobil için) -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

