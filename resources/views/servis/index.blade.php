<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Servis Durumu - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <style>
        .servis-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-top: 20px;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 25px;
            border-bottom: 1px solid #e0e0e0;
            background: #f8f9fa;
        }
        .search-filter {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }
        .search-box input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
        }
        .filter-select {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: white;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f5f5f5;
        }
        th {
            text-align: left;
            padding: 15px 20px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
        }
        td {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
        }
        tr:hover {
            background: #f9f9f9;
            cursor: pointer;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-beklemede {
            background: #fff3cd;
            color: #856404;
        }
        .status-islemde {
            background: #cce5ff;
            color: #004085;
        }
        .status-tamamlandi {
            background: #d4edda;
            color: #155724;
        }
        .status-iptal {
            background: #f8d7da;
            color: #721c24;
        }
        .pagination-wrapper {
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e0e0e0;
        }
        .pagination {
            display: flex;
            gap: 5px;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .page-item {
            display: inline-block;
        }
        .page-link {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s;
        }
        .page-link:hover {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .page-item.active .page-link {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .priority-badge {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .priority-normal { background: #28a745; }
        .priority-acil { background: #ffc107; }
        .priority-cok-acil { background: #dc3545; }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-view {
            background: #17a2b8;
            color: white;
        }
        .btn-complete {
            background: #28a745;
            color: white;
        }
        .btn-action:hover {
            opacity: 0.8;
            transform: translateY(-1px);
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

        <!-- Ana İçerik -->
        <main class="main-content">
            <div class="content-header">
                <h2>🔧 Servis Durumu</h2>
                <a href="{{ route('servis.create') }}" class="btn btn-primary">➕ Yeni Servis Ekle</a>
            </div>

            <div class="servis-table">
                <div class="table-header">
                    <div class="search-filter">
                        <div class="search-box">
                            <input type="text" id="searchInput" placeholder="Servis no veya müşteri adı ile ara..." value="{{ request('search') }}">
                        </div>
                        <select class="filter-select" id="durumFilter">
                            <option value="">Tüm Durumlar</option>
                            <option value="Beklemede" {{ request('durum') == 'Beklemede' ? 'selected' : '' }}>Beklemede</option>
                            <option value="İşlemde" {{ request('durum') == 'İşlemde' ? 'selected' : '' }}>İşlemde</option>
                            <option value="Tamamlandı" {{ request('durum') == 'Tamamlandı' ? 'selected' : '' }}>Tamamlandı</option>
                            <option value="İptal" {{ request('durum') == 'İptal' ? 'selected' : '' }}>İptal</option>
                        </select>
                    </div>
                    <div>
                        <strong>{{ $servisler->total() }}</strong> servis
                    </div>
                </div>

                @if($servisler->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Servis No</th>
                            <th>Müşteri</th>
                            <th>Marka/Model</th>
                            <th>Arıza</th>
                            <th>Öncelik</th>
                            <th>Durum</th>
                            <th>Tarih</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servisler as $servis)
                        <tr>
                            <td><strong>{{ $servis->servis_no }}</strong></td>
                            <td>
                                <div>{{ $servis->cariHesap->cari_hesap_adi }}</div>
                                <small style="color: #999;">{{ $servis->cariHesap->musteri_kodu }}</small>
                            </td>
                            <td>{{ $servis->marka }} {{ $servis->model }}</td>
                            <td>{{ $servis->ariza_tanimi ?? '-' }}</td>
                            <td>
                                <span class="priority-badge priority-{{ strtolower(str_replace(' ', '-', $servis->oncelik_durumu ?? 'normal')) }}"></span>
                                {{ $servis->oncelik_durumu ?? 'Normal' }}
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '', $servis->durum)) }}">
                                    {{ $servis->durum }}
                                </span>
                            </td>
                            <td>{{ $servis->created_at->format('d.m.Y') }}</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="window.location.href='{{ route('servis.show', $servis->id) }}'">
                                        👁️ Detay
                                    </button>
                                    @if($servis->durum !== 'Tamamlandı')
                                    <button class="btn-action btn-complete" onclick="window.location.href='{{ route('servis.complete', $servis->id) }}'">
                                        ✅ Tamamla
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <div>
                        {{ $servisler->firstItem() ?? 0 }} - {{ $servisler->lastItem() ?? 0 }} / {{ $servisler->total() }} kayıt
                    </div>
                    <div>
                        {{ $servisler->appends(request()->query())->links() }}
                    </div>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon">🔍</div>
                    <h3>Henüz servis kaydı yok</h3>
                    <p>Yeni bir servis eklemek için yukarıdaki "Yeni Servis Ekle" butonunu kullanın.</p>
                </div>
                @endif
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

            $('#sidebarOverlay').on('click', function() {
                $('#sidebar').removeClass('active');
                $('#sidebarOverlay').removeClass('active');
            });

            // Submenu toggle
            $('.menu-item.has-submenu').on('click', function(e) {
                e.preventDefault();
                const submenuId = $(this).data('submenu');
                const $submenu = $('#submenu-' + submenuId);
                
                $('.submenu').not($submenu).removeClass('open');
                $('.menu-item.has-submenu').not(this).removeClass('open');
                
                $(this).toggleClass('open');
                $submenu.toggleClass('open');
            });

            // Arama
            let searchTimeout;
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                const search = $(this).val();
                const durum = $('#durumFilter').val();
                
                searchTimeout = setTimeout(function() {
                    const params = new URLSearchParams();
                    if (search) params.append('search', search);
                    if (durum) params.append('durum', durum);
                    
                    window.location.href = '{{ route("servis.index") }}?' + params.toString();
                }, 500);
            });

            // Durum filtresi
            $('#durumFilter').on('change', function() {
                const search = $('#searchInput').val();
                const durum = $(this).val();
                
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (durum) params.append('durum', durum);
                
                window.location.href = '{{ route("servis.index") }}?' + params.toString();
            });
        });
    </script>
</body>
</html>

