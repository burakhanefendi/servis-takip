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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }
        .filter-select {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        .filter-select:hover {
            border-color: #667eea;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }
        .filter-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
        .servis-durum-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }
        .durum-bekliyor {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
        }
        .durum-bekliyor-acil {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4);
            animation: pulse-red 2s infinite;
        }
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4); }
            50% { box-shadow: 0 4px 12px rgba(220, 53, 69, 0.6); }
        }
        .durum-islemde {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(33, 150, 243, 0.3);
        }
        .durum-tamamlandi {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
        }
        .gun-farki {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }
        .pagination-wrapper {
            padding: 25px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
            border-top: 1px solid #e0e0e0;
            background: #f8f9fa;
        }
        .pagination-info {
            color: #495057;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pagination-info i {
            color: #6c757d;
        }
        .pagination-info strong {
            color: #2c3e50;
        }
        .pagination-links {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pagination-links nav {
            display: flex;
        }
        /* Bootstrap pagination özelleştirme - Site teması ile uyumlu */
        .pagination {
            margin: 0;
            gap: 4px;
        }
        .page-link {
            color: #2c3e50;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 2px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }
        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .page-item.disabled .page-link {
            cursor: not-allowed;
            opacity: 0.5;
            background: #f8f9fa;
        }
        .page-item.disabled .page-link:hover {
            transform: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        /* İlk/Son sayfa butonları için özel stil */
        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            font-weight: 600;
        }
        .page-item:first-child .page-link::before {
            content: '← ';
        }
        .page-item:last-child .page-link::after {
            content: ' →';
        }
        /* Mobil uyum */
        @media (max-width: 768px) {
            .pagination-wrapper {
                padding: 20px 15px;
            }
            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
            .page-link {
                padding: 8px 12px;
                font-size: 13px;
            }
            .pagination-info {
                font-size: 13px;
                text-align: center;
            }
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
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }
        .stat-item {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-left: 4px solid #e0e0e0;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
        }
        .stat-item.stat-clickable {
            cursor: pointer;
            user-select: none;
        }
        .stat-item.stat-clickable:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .stat-item.stat-clickable:active {
            transform: translateY(-3px) scale(1.01);
        }
        .stat-item:nth-child(1) { border-left-color: #dc3545; }
        .stat-item:nth-child(2) { border-left-color: #ffc107; }
        .stat-item:nth-child(3) { border-left-color: #28a745; }
        .stat-item:nth-child(4) { border-left-color: #667eea; }
        .stat-item.stat-active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-left-width: 6px;
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
        }
        .stat-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }
        .stat-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .stat-label i {
            font-size: 16px;
        }
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            line-height: 1;
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
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            <h1><i class="fas fa-tools"></i> Servis Takip Sistemi</h1>
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
                <h2><i class="fas fa-clipboard-list"></i> Periyodik Bakım Listesi</h2>
                <div style="display: flex; gap: 10px;">
                    <button onclick="document.getElementById('importModal').style.display='flex'" class="btn btn-secondary">
                        <i class="fas fa-file-import"></i> Excel İçe Aktar
                    </button>
                    <a href="{{ route('bakim.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Bakım Ekle</a>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #155724;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            @if(session('import_debug') && count(session('import_debug')) > 0)
            <div class="alert alert-info" style="background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #0c5460;">
                <strong><i class="fas fa-info-circle"></i> Debug Bilgisi:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach(session('import_debug') as $debugInfo)
                        <li>{{ $debugInfo }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="alert alert-warning" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #856404;">
                <strong><i class="fas fa-exclamation-triangle"></i> Uyarılar:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <details style="margin-top: 15px; cursor: pointer;">
                    <summary style="font-weight: bold; color: #721c24;">Hatalar Nasıl Çözülür?</summary>
                    <div style="margin-top: 10px; padding: 10px; background: white; border-radius: 5px;">
                        <p><strong>• Müşteri bulunamadı hatası:</strong> Müşteri ünvanının sistemde kayıtlı olduğundan emin olun. Cari listesinden kontrol edin.</p>
                        <p><strong>• Geçersiz tarih formatı:</strong> Tarihleri <code>2024-07-09</code> veya <code>09.07.2024</code> formatında girin.</p>
                        <p><strong>• Eksik kolon hatası:</strong> CSV dosyanızın tüm kolonları içerdiğinden emin olun. Excel'den "CSV UTF-8 (Virgülle ayrılmış)" olarak kaydedin.</p>
                    </div>
                </details>
            </div>
            @endif

            <!-- İstatistikler -->
            <div class="stats-card">
                <div class="stat-item stat-clickable {{ ($durumFilter ?? null) === 'gecmis' ? 'stat-active' : '' }}" onclick="filterByDurum('gecmis')">
                    <div class="stat-label"><i class="fas fa-exclamation-triangle"></i> Geçmiş Bakımlar</div>
                    <div class="stat-value stat-gecmis">{{ $stats['gecmis'] ?? 0 }}</div>
                    <small style="color: #999; font-size: 12px;">Tarihi geçmiş</small>
                    @if(($durumFilter ?? null) === 'gecmis')
                    <div class="stat-badge"><i class="fas fa-check"></i> Aktif Filtre</div>
                    @endif
                </div>
                <div class="stat-item stat-clickable {{ ($durumFilter ?? null) === 'yaklasan' ? 'stat-active' : '' }}" onclick="filterByDurum('yaklasan')">
                    <div class="stat-label"><i class="fas fa-clock"></i> Yaklaşan Bakımlar</div>
                    <div class="stat-value stat-yaklasan">{{ $stats['yaklasan'] ?? 0 }}</div>
                    <small style="color: #999; font-size: 12px;">7 gün içinde</small>
                    @if(($durumFilter ?? null) === 'yaklasan')
                    <div class="stat-badge"><i class="fas fa-check"></i> Aktif Filtre</div>
                    @endif
                </div>
                <div class="stat-item stat-clickable {{ ($durumFilter ?? null) === 'normal' ? 'stat-active' : '' }}" onclick="filterByDurum('normal')">
                    <div class="stat-label"><i class="fas fa-check-circle"></i> Normal Bakımlar</div>
                    <div class="stat-value stat-normal">{{ $stats['normal'] ?? 0 }}</div>
                    <small style="color: #999; font-size: 12px;">7 günden fazla</small>
                    @if(($durumFilter ?? null) === 'normal')
                    <div class="stat-badge"><i class="fas fa-check"></i> Aktif Filtre</div>
                    @endif
                </div>
                <div class="stat-item stat-clickable {{ empty($durumFilter) ? 'stat-active' : '' }}" onclick="filterByDurum('')">
                    <div class="stat-label"><i class="fas fa-list-ul"></i> Toplam Bakım</div>
                    <div class="stat-value" style="color: #2c3e50;">{{ $stats['toplam'] ?? 0 }}</div>
                    <small style="color: #999; font-size: 12px;">Tüm kayıtlar</small>
                    @if(empty($durumFilter))
                    <div class="stat-badge"><i class="fas fa-check"></i> Aktif Filtre</div>
                    @endif
                </div>
            </div>

            @if(!empty($durumFilter))
            <div style="background: 
                @if($durumFilter === 'gecmis') #dc3545 
                @elseif($durumFilter === 'yaklasan') #ffc107 
                @else #28a745 
                @endif; 
                color: white; padding: 12px 20px; border-radius: 10px; margin-bottom: 15px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-filter" style="font-size: 18px;"></i>
                    <span style="font-weight: 500;">
                        <strong>Filtre Aktif:</strong> 
                        @if($durumFilter === 'gecmis')
                            Sadece geçmiş bakımlar gösteriliyor
                        @elseif($durumFilter === 'yaklasan')
                            Sadece yaklaşan bakımlar (7 gün içinde) gösteriliyor
                        @else
                            Sadece normal bakımlar (7+ gün sonra) gösteriliyor
                        @endif
                    </span>
                </div>
                <button onclick="filterByDurum('')" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); color: white; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    <i class="fas fa-times"></i> Filtreyi Kaldır
                </button>
            </div>
            @endif

            <div class="bakim-table">
                <div class="table-header">
                    <div class="search-filter">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchInput" placeholder="Servis no veya müşteri adı ile ara..." value="{{ request('search') }}">
                        </div>
                        <select class="filter-select" id="servisDurumuFilter" style="width: auto; min-width: 180px; font-weight: 500;">
                            <option value="">Tüm Durumlar</option>
                            <option value="Bekliyor" {{ request('servis_durumu') == 'Bekliyor' ? 'selected' : '' }}>
                                🟡 Bekliyor
                            </option>
                            <option value="İşlemde" {{ request('servis_durumu') == 'İşlemde' ? 'selected' : '' }}>
                                🔵 İşlemde
                            </option>
                            <option value="Tamamlandı" {{ request('servis_durumu') == 'Tamamlandı' ? 'selected' : '' }}>
                                🟢 Tamamlandı
                            </option>
                        </select>
                        <select class="filter-select" id="sortByFilter" style="width: auto; min-width: 220px; font-weight: 500;">
                            <option value="smart" {{ request('sort_by', 'smart') == 'smart' ? 'selected' : '' }}>
                                ✨ Akıllı Sıralama
                            </option>
                            <option value="date_asc" {{ request('sort_by') == 'date_asc' ? 'selected' : '' }}>
                                📅 Tarihe Göre (Eskiden Yeniye)
                            </option>
                            <option value="date_desc" {{ request('sort_by') == 'date_desc' ? 'selected' : '' }}>
                                📅 Tarihe Göre (Yeniden Eskiye)
                            </option>
                        </select>
                        <select class="filter-select" id="perPageFilter" style="width: auto; min-width: 120px;">
                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 kayıt</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50 kayıt</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 kayıt</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 kayıt</option>
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
                            <th>Bakım Durumu</th>
                            <th>Servis Durumu</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servisler as $servis)
                        <tr onclick="window.location.href='{{ route('bakim.show', $servis->id) }}'">
                            <td><strong>{{ $servis->servis_no }}</strong></td>
                            <td>
                                <div>{{ $servis->cariHesap->cari_hesap_adi }}</div>
                                <small style="color: #999;">{{ $servis->cariHesap->musteri_kodu }}</small>
                                @if($servis->sms_hatirlatma)
                                <span class="sms-icon" title="SMS Hatırlatma Aktif"><i class="fas fa-mobile-alt"></i></span>
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
                                        <i class="fas fa-circle" style="color: #dc3545;"></i> Geçmiş
                                    @elseif($servis->bakim_durum == 'yaklasan')
                                        <i class="fas fa-circle" style="color: #ffc107;"></i> Yaklaşan
                                    @else
                                        <i class="fas fa-circle" style="color: #28a745;"></i> Normal
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($servis->durum == 'Bekliyor')
                                    @if(isset($servis->aciliyet) && $servis->aciliyet == 'yuksek')
                                        <span class="servis-durum-badge durum-bekliyor-acil">
                                            <i class="fas fa-exclamation-triangle"></i> Bekliyor
                                        </span>
                                    @else
                                        <span class="servis-durum-badge durum-bekliyor">
                                            <i class="fas fa-clock"></i> Bekliyor
                                        </span>
                                    @endif
                                @elseif($servis->durum == 'İşlemde')
                                    <span class="servis-durum-badge durum-islemde">
                                        <i class="fas fa-cog fa-spin"></i> İşlemde
                                    </span>
                                @elseif($servis->durum == 'Tamamlandı')
                                    <span class="servis-durum-badge durum-tamamlandi">
                                        <i class="fas fa-check-circle"></i> Tamamlandı
                                    </span>
                                @else
                                    <span class="servis-durum-badge durum-bekliyor">
                                        <i class="fas fa-clock"></i> {{ $servis->durum ?? 'Bekliyor' }}
                                    </span>
                                @endif
                            </td>
                            <td onclick="event.stopPropagation();">
                                <button class="btn-detay" onclick="window.location.href='{{ route('bakim.show', $servis->id) }}'">
                                    <i class="fas fa-eye"></i> Detay
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <i class="fas fa-list"></i> 
                        Gösterilen: <strong>{{ $servisler->firstItem() ?? 0 }} - {{ $servisler->lastItem() ?? 0 }}</strong> / 
                        Toplam: <strong>{{ $servisler->total() }}</strong> kayıt
                    </div>
                    <div class="pagination-links">
                        {{ $servisler->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3>Henüz periyodik bakım kaydı yok</h3>
                    <p>Periyodik bakımı olan servisler burada görünecektir.</p>
                </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Import Modal -->
    <div id="importModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 30px; max-width: 600px; width: 90%; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;"><i class="fas fa-file-import"></i> Bakım Kayıtlarını İçe Aktar</h3>
                <button onclick="document.getElementById('importModal').style.display='none'" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #999;">&times;</button>
            </div>

            <div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                <strong><i class="fas fa-info-circle"></i> Excel Formatı:</strong>
                <p style="margin: 10px 0 0 0; color: #0d47a1; font-size: 14px;">
                    Excel dosyanızı <strong>CSV</strong> formatında kaydedin. Kolonlar şu sırada olmalıdır:<br>
                    <code style="background: white; padding: 5px; display: block; margin-top: 10px; border-radius: 4px; font-size: 12px;">
                        Durum, Bakım Kodu, Müşteri Ünvanı, Marka, Model, Bakım Tarihi, Bakım İçeriği, Bakım Notu, Personel, Bakım Periyodu, Bakım Lokasyonu
                    </code>
                </p>
                <p style="margin: 10px 0 0 0; color: #0d47a1; font-size: 13px;">
                    <strong>Tarih Formatları:</strong> <code style="background: white; padding: 2px 6px; border-radius: 3px;">2024-07-09 13:39:00</code> 
                    veya <code style="background: white; padding: 2px 6px; border-radius: 3px;">09.07.2024</code>
                </p>
                <p style="margin: 5px 0 0 0; color: #0d47a1; font-size: 13px;">
                    <strong>Önemli:</strong> Müşteri Ünvanı sistemde kayıtlı bir müşteriyle eşleşmelidir.
                </p>
            </div>

            <form action="{{ route('bakim.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        <i class="fas fa-file-csv"></i> CSV Dosyası Seç
                    </label>
                    <input type="file" name="file" accept=".csv,.txt" required 
                        style="width: 100%; padding: 10px; border: 2px dashed #ddd; border-radius: 8px; cursor: pointer;">
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="document.getElementById('importModal').style.display='none'" 
                        style="padding: 10px 20px; background: #f5f5f5; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        İptal
                    </button>
                    <button type="submit" 
                        style="padding: 10px 20px; background: #2196f3; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                        <i class="fas fa-upload"></i> İçe Aktar
                    </button>
                </div>
            </form>
        </div>
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

            // Durum filtreleme fonksiyonu (İstatistik kartları için)
            window.filterByDurum = function(durum) {
                const search = $('#searchInput').val();
                const servisDurumu = $('#servisDurumuFilter').val();
                const perPage = $('#perPageFilter').val();
                const sortBy = $('#sortByFilter').val();
                
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (servisDurumu) params.append('servis_durumu', servisDurumu);
                if (perPage) params.append('per_page', perPage);
                if (sortBy) params.append('sort_by', sortBy);
                if (durum) params.append('durum_filter', durum);
                
                window.location.href = '{{ route("bakim.index") }}?' + params.toString();
            };

            // Arama
            let searchTimeout;
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                const search = $(this).val();
                const servisDurumu = $('#servisDurumuFilter').val();
                const perPage = $('#perPageFilter').val();
                const sortBy = $('#sortByFilter').val();
                const durumFilter = '{{ $durumFilter ?? "" }}';
                
                searchTimeout = setTimeout(function() {
                    const params = new URLSearchParams();
                    if (search) params.append('search', search);
                    if (servisDurumu) params.append('servis_durumu', servisDurumu);
                    if (perPage) params.append('per_page', perPage);
                    if (sortBy) params.append('sort_by', sortBy);
                    if (durumFilter) params.append('durum_filter', durumFilter);
                    
                    window.location.href = '{{ route("bakim.index") }}?' + params.toString();
                }, 500);
            });

            // Servis durumu filtresi
            $('#servisDurumuFilter').on('change', function() {
                const search = $('#searchInput').val();
                const servisDurumu = $(this).val();
                const perPage = $('#perPageFilter').val();
                const sortBy = $('#sortByFilter').val();
                const durumFilter = '{{ $durumFilter ?? "" }}';
                
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (servisDurumu) params.append('servis_durumu', servisDurumu);
                if (perPage) params.append('per_page', perPage);
                if (sortBy) params.append('sort_by', sortBy);
                if (durumFilter) params.append('durum_filter', durumFilter);
                
                window.location.href = '{{ route("bakim.index") }}?' + params.toString();
            });

            // Sıralama filtresi
            $('#sortByFilter').on('change', function() {
                const search = $('#searchInput').val();
                const servisDurumu = $('#servisDurumuFilter').val();
                const perPage = $('#perPageFilter').val();
                const sortBy = $(this).val();
                const durumFilter = '{{ $durumFilter ?? "" }}';
                
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (servisDurumu) params.append('servis_durumu', servisDurumu);
                if (perPage) params.append('per_page', perPage);
                if (sortBy) params.append('sort_by', sortBy);
                if (durumFilter) params.append('durum_filter', durumFilter);
                
                window.location.href = '{{ route("bakim.index") }}?' + params.toString();
            });

            // Sayfa başına kayıt filtresi
            $('#perPageFilter').on('change', function() {
                const search = $('#searchInput').val();
                const servisDurumu = $('#servisDurumuFilter').val();
                const perPage = $(this).val();
                const sortBy = $('#sortByFilter').val();
                const durumFilter = '{{ $durumFilter ?? "" }}';
                
                const params = new URLSearchParams();
                if (search) params.append('search', search);
                if (servisDurumu) params.append('servis_durumu', servisDurumu);
                if (perPage) params.append('per_page', perPage);
                if (sortBy) params.append('sort_by', sortBy);
                if (durumFilter) params.append('durum_filter', durumFilter);
                
                window.location.href = '{{ route("bakim.index") }}?' + params.toString();
            });
        });
    </script>
</body>
</html>


