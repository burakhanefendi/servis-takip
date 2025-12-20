<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bakım Listesi - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bakim-table {
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
        .bakim-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .bakim-gecmis {
            background: #f8d7da;
            color: #721c24;
        }
        .bakim-yaklasan {
            background: #fff3cd;
            color: #856404;
        }
        .bakim-normal {
            background: #d4edda;
            color: #155724;
        }
        .gun-farki {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
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
        .stats-card {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 24px;
            font-weight: 700;
        }
        .stat-gecmis { color: #dc3545; }
        .stat-yaklasan { color: #ffc107; }
        .stat-normal { color: #28a745; }
        .btn-detay {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
        }
        .btn-detay:hover {
            opacity: 0.8;
        }
        .sms-icon {
            display: inline-block;
            margin-left: 5px;
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
                <h2>📋 Periyodik Bakım Listesi</h2>
                <a href="{{ route('bakim.create') }}" class="btn btn-primary">➕ Bakım Ekle</a>
            </div>

            <!-- İstatistikler -->
            <div class="stats-card">
                <div class="stat-item">
                    <div class="stat-label">Geçmiş Bakımlar</div>
                    <div class="stat-value stat-gecmis">{{ $servisler->where('bakim_durum', 'gecmis')->count() }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Yaklaşan Bakımlar (7 Gün)</div>
                    <div class="stat-value stat-yaklasan">{{ $servisler->where('bakim_durum', 'yaklasan')->count() }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Normal Bakımlar</div>
                    <div class="stat-value stat-normal">{{ $servisler->where('bakim_durum', 'normal')->count() }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Toplam Bakım</div>
                    <div class="stat-value">{{ $servisler->total() }}</div>
                </div>
            </div>

            <div class="bakim-table">
                <div class="table-header">
                    <div class="search-filter">
                        <div class="search-box">
                            <input type="text" id="searchInput" placeholder="Servis no veya müşteri adı ile ara..." value="{{ request('search') }}">
                        </div>
                        <select class="filter-select" id="periyodikBakimFilter">
                            <option value="">Tüm Bakımlar</option>
                            <option value="Aylık" {{ request('periyodik_bakim') == 'Aylık' ? 'selected' : '' }}>Aylık</option>
                            <option value="3 Aylık" {{ request('periyodik_bakim') == '3 Aylık' ? 'selected' : '' }}>3 Aylık</option>
                            <option value="6 Aylık" {{ request('periyodik_bakim') == '6 Aylık' ? 'selected' : '' }}>6 Aylık</option>
                            <option value="Yıllık" {{ request('periyodik_bakim') == 'Yıllık' ? 'selected' : '' }}>Yıllık</option>
                            <option value="2 Yıllık" {{ request('periyodik_bakim') == '2 Yıllık' ? 'selected' : '' }}>2 Yıllık</option>
                        </select>
                    </div>
                </div>

                @if($servisler->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Servis No</th>
                            <th>Müşteri</th>
                            <th>Marka/Model</th>
                            <th>Periyodik Bakım</th>
                            <th>Bakım Tarihi</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servisler as $servis)
                        <tr onclick="window.location.href='{{ route('servis.show', $servis->id) }}'">
                            <td><strong>{{ $servis->servis_no }}</strong></td>
                            <td>
                                <div>{{ $servis->cariHesap->cari_hesap_adi }}</div>
                                <small style="color: #999;">{{ $servis->cariHesap->musteri_kodu }}</small>
                                @if($servis->sms_hatirlatma)
                                <span class="sms-icon" title="SMS Hatırlatma Aktif">📱</span>
                                @endif
                            </td>
                            <td>{{ $servis->marka }} {{ $servis->model }}</td>
                            <td>{{ $servis->periyodik_bakim }}</td>
                            <td>
                                <div><strong>{{ $servis->bakim_tarihi ? $servis->bakim_tarihi->format('d.m.Y') : '-' }}</strong></div>
                                @if($servis->gun_farki !== null)
                                <div class="gun-farki">
                                    @if($servis->bakim_durum == 'gecmis')
                                        {{ $servis->gun_farki }} gün geçti
                                    @elseif($servis->bakim_durum == 'yaklasan')
                                        {{ $servis->gun_farki }} gün kaldı
                                    @else
                                        {{ $servis->gun_farki }} gün sonra
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="bakim-badge bakim-{{ $servis->bakim_durum }}">
                                    @if($servis->bakim_durum == 'gecmis')
                                        🔴 Geçmiş
                                    @elseif($servis->bakim_durum == 'yaklasan')
                                        🟡 Yaklaşan
                                    @else
                                        🟢 Normal
                                    @endif
                                </span>
                            </td>
                            <td onclick="event.stopPropagation();">
                                <button class="btn-detay" onclick="window.location.href='{{ route('servis.show', $servis->id) }}'">
                                    👁️ Detay
                                </button>
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
                    <div class="empty-state-icon">📅</div>
                    <h3>Henüz periyodik bakım kaydı yok</h3>
                    <p>Periyodik bakımı olan servisler burada görünecektir.</p>
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
                const bakim = $('#periyodikBakimFilter').val();
                
                searchTimeout = setTimeout(function() {
                    const params = new URLSearchParams();
                    if (search) params.append('search', search);
                    if (bakim) params.append('periyodik_bakim', bakim);
                    
                    window.location.href = '{{ route("bakim.index") }}?' + params.toString();
                }, 500);
            });

            // Periyodik bakım filtresi
            $('#periyodikBakimFilter').on('change', function() {
                const search = $('#searchInput').val();
                const bakim = $(this).val();
                
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (bakim) params.append('periyodik_bakim', bakim);
                
                window.location.href = '{{ route("bakim.index") }}?' + params.toString();
            });
        });
    </script>
</body>
</html>

