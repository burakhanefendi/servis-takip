<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bakım Detay - {{ $bakim->servis_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bakim-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 25px 30px;
            margin-bottom: 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .bakim-header-left h2 {
            margin: 0 0 8px 0;
            font-size: 24px;
            font-weight: 600;
        }
        .bakim-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
        }
        .bakim-header-right {
            display: flex;
            gap: 12px;
        }
        .btn-header {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-header:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        .btn-header.btn-success {
            background: #28a745;
            border-color: #28a745;
        }
        .btn-header.btn-success:hover {
            background: #218838;
        }
        .btn-header.btn-primary {
            background: #007bff;
            border-color: #007bff;
        }
        .btn-header.btn-primary:hover {
            background: #0056b3;
        }
        .btn-header.btn-danger {
            background: #dc3545;
            border-color: #dc3545;
        }
        .btn-header.btn-danger:hover {
            background: #c82333;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .detail-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        .card-header i {
            color: #667eea;
            font-size: 20px;
        }
        .card-header h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .detail-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 15px;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .detail-label {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }
        .detail-value {
            font-size: 15px;
            color: #333;
            font-weight: 600;
        }
        .status-bekliyor { background: #ffc107; color: #856404; }
        .status-gecmis { background: #dc3545; color: white; }
        .status-yaklasan { background: #ff9800; color: white; }
        .status-normal { background: #28a745; color: white; }
        .alert-info {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #0d47a1;
        }
        .gecmis-bakimlar {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .bakimlar-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .bakimlar-table thead {
            background: #f8f9fa;
        }
        .bakimlar-table th {
            text-align: left;
            padding: 12px 15px;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 14px;
        }
        .bakimlar-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            font-size: 14px;
        }
        .bakimlar-table tr:hover {
            background: #f9f9f9;
        }
        .bakim-status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        @media (max-width: 768px) {
            .bakim-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .bakim-header-right {
                width: 100%;
                flex-direction: column;
            }
            .btn-header {
                width: 100%;
                justify-content: center;
            }
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
            <!-- Bakım Header -->
            <div class="bakim-header">
                <div class="bakim-header-left">
                    <h2><i class="fas fa-calendar-check"></i> Bakım #{{ $bakim->id }}</h2>
                    <span class="bakim-badge status-{{ $bakim->bakim_durum }}">
                        @if($bakim->bakim_durum == 'bekliyor')
                            BEKLİYOR
                        @elseif($bakim->bakim_durum == 'gecmis')
                            GEÇMİŞ
                        @elseif($bakim->bakim_durum == 'yaklasan')
                            YAKLAŞAN
                        @else
                            NORMAL
                        @endif
                    </span>
                </div>
                <div class="bakim-header-right">
                    <a href="{{ route('bakim.index') }}" class="btn-header">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                    <button class="btn-header btn-success">
                        <i class="fas fa-redo"></i> Servise Dönüştür
                    </button>
                    <button class="btn-header btn-primary">
                        <i class="fas fa-edit"></i> Düzenle
                    </button>
                    <button class="btn-header btn-danger">
                        <i class="fas fa-trash"></i> Sil
                    </button>
                </div>
            </div>

            <!-- Müşteri Bilgileri -->
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        <h3>{{ $bakim->cariHesap->cari_hesap_adi }}</h3>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">ADRES</span>
                            <span class="detail-value">{{ $bakim->adres ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">GSM</span>
                            <span class="detail-value">{{ $bakim->cariHesap->gsm ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">E-POSTA</span>
                            <span class="detail-value">{{ $bakim->cariHesap->eposta ?? '-' }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">TELEFON</span>
                            <span class="detail-value">{{ $bakim->cariHesap->sabit_telefon ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-item">
                            <span class="detail-label">YETKİLİ</span>
                            <span class="detail-value">-</span>
                        </div>
                    </div>
                </div>
            </div>

            @if($bakim->bakim_durum == 'bekliyor')
            <div class="alert-info">
                <strong><i class="fas fa-info-circle"></i> Bilgi:</strong> Bakım hatırlatması bekliyor durumunda. Bakım tarihi geldiğinde otomatik olarak bildirim gönderilecektir.
            </div>
            @endif

            <!-- Bakım Bilgileri -->
            <div class="detail-card" style="margin-bottom: 25px;">
                <div class="card-header">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Bakım Bilgileri</h3>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-laptop"></i> Cihaz Marka</span>
                        <span class="detail-value">{{ $bakim->marka ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-tag"></i> Cihaz Model</span>
                        <span class="detail-value">{{ $bakim->model ?? '-' }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-calendar-alt"></i> Bakım Tarihi</span>
                        <span class="detail-value">{{ $bakim->bakim_tarihi ? $bakim->bakim_tarihi->format('d.m.Y H:i') : '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-calendar-alt"></i> Bakım Kodu</span>
                        <span class="detail-value">#{{ $bakim->id }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-map-marker-alt"></i> Lokasyon</span>
                        <span class="detail-value">{{ $bakim->bakim_lokasyonu ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-redo"></i> Periyot</span>
                        <span class="detail-value">{{ $bakim->periyodik_bakim ?? '-' }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-wrench"></i> Bakım İçeriği</span>
                        <span class="detail-value">{{ $bakim->bakim_icerigi ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-clock"></i> Bakım Notu</span>
                        <span class="detail-value">{{ $bakim->bakim_notu ?? 'YENİ' }}</span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-clock"></i> Periyot</span>
                        <span class="detail-value">{{ $bakim->periyodik_bakim ?? '-' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-bell"></i> SMS Bildirimi</span>
                        <span class="detail-value">
                            @if($bakim->sms_hatirlatma)
                                <span style="color: #28a745;">Evet</span> ({{ $bakim->hatirlatma_zamani }})
                            @else
                                Hayır
                            @endif
                        </span>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-item">
                        <span class="detail-label"><i class="fas fa-user"></i> Personel</span>
                        <span class="detail-value">{{ $bakim->personel ?? 'ŞULENUR AKAR BOZKURT' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Durum</span>
                        <span class="detail-value">
                            <span class="bakim-status-badge status-{{ $bakim->bakim_durum }}">
                                @if($bakim->bakim_durum == 'bekliyor')
                                    Bekliyor
                                @elseif($bakim->bakim_durum == 'gecmis')
                                    Geçmiş
                                @elseif($bakim->bakim_durum == 'yaklasan')
                                    Yaklaşan
                                @else
                                    Normal
                                @endif
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Geçmiş Servisler -->
            <div class="gecmis-bakimlar">
                <div class="card-header">
                    <i class="fas fa-history"></i>
                    <h3>Bu Müşterinin Geçmiş Servisleri ({{ $gecmisBakimlar->count() }})</h3>
                </div>
                @if($gecmisBakimlar->count() > 0)
                <table class="bakimlar-table">
                    <thead>
                        <tr>
                            <th>Servis No</th>
                            <th>Tamamlanma Tarihi</th>
                            <th>Periyot</th>
                            <th>Marka/Model</th>
                            <th>Yapılan İşlem</th>
                            <th>Toplam Tutar</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gecmisBakimlar as $gecmisBakim)
                        <tr>
                            <td><strong>{{ $gecmisBakim->servis_no }}</strong></td>
                            <td>
                                <div><strong>{{ $gecmisBakim->tamamlanma_tarihi ? $gecmisBakim->tamamlanma_tarihi->format('d.m.Y') : '-' }}</strong></div>
                                @if($gecmisBakim->bakim_tarihi)
                                <small style="color: #999;">Bakım: {{ $gecmisBakim->bakim_tarihi->format('d.m.Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $gecmisBakim->periyodik_bakim ?? '-' }}</td>
                            <td>{{ $gecmisBakim->marka }} {{ $gecmisBakim->model }}</td>
                            <td>{{ Str::limit($gecmisBakim->yapilan_islemler ?? $gecmisBakim->ariza_tanimlari ?? '-', 50) }}</td>
                            <td><strong>{{ number_format($gecmisBakim->vergiler_dahil_toplam ?? 0, 2) }} ₺</strong></td>
                            <td>
                                <a href="{{ route('servis.show', $gecmisBakim->id) }}" class="btn-detay" style="display: inline-block; background: #17a2b8; color: white; padding: 5px 12px; border-radius: 6px; text-decoration: none; font-size: 13px;">
                                    <i class="fas fa-eye"></i> Detay
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <i class="fas fa-inbox" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                    <p>Bu müşterinin başka servis kaydı bulunmamaktadır.</p>
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
        });
    </script>
</body>
</html>

