<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Detay - {{ $servis->servis_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 25px;
            margin-bottom: 20px;
        }
        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        .servis-no {
            font-size: 24px;
            font-weight: 700;
            color: #007bff;
        }
        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .info-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .info-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .products-table th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e0e0e0;
        }
        .products-table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
                <h2>📋 Servis Detayı</h2>
                <a href="{{ route('servis.index') }}" class="btn btn-secondary">⬅ Geri Dön</a>
            </div>

            <!-- Servis Başlık -->
            <div class="detail-card">
                <div class="detail-header">
                    <div>
                        <div class="servis-no">{{ $servis->servis_no }}</div>
                        <small style="color: #666;">{{ $servis->created_at->format('d.m.Y H:i') }}</small>
                    </div>
                    <span class="status-badge status-{{ strtolower(str_replace(' ', '', $servis->durum)) }}">
                        {{ $servis->durum }}
                    </span>
                </div>

                @if($servis->durum !== 'Tamamlandı')
                <div class="btn-group">
                    <a href="{{ route('servis.complete', $servis->id) }}" class="btn btn-primary">
                        ✅ Servisi Tamamla
                    </a>
                </div>
                @endif
            </div>

            <!-- Müşteri Bilgileri -->
            <div class="detail-card">
                <h3 class="section-title">👤 Müşteri Bilgileri</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Cari Hesap</div>
                        <div class="info-value">{{ $servis->cariHesap->cari_hesap_adi }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Müşteri Kodu</div>
                        <div class="info-value">{{ $servis->cariHesap->musteri_kodu }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">E-posta</div>
                        <div class="info-value">{{ $servis->eposta ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">GSM</div>
                        <div class="info-value">{{ $servis->gsm ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">İl/İlçe</div>
                        <div class="info-value">{{ $servis->il }} / {{ $servis->ilce }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Adres</div>
                        <div class="info-value">{{ $servis->adres ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Ürün Bilgileri -->
            <div class="detail-card">
                <h3 class="section-title">📦 Ürün Bilgileri</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Marka</div>
                        <div class="info-value">{{ $servis->marka ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Model</div>
                        <div class="info-value">{{ $servis->model ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Seri No</div>
                        <div class="info-value">{{ $servis->seri_numarasi ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Ürün Cinsi</div>
                        <div class="info-value">{{ $servis->urun_cinsi ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Garanti Durumu</div>
                        <div class="info-value">{{ $servis->garanti_durumu ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Ürün Rengi</div>
                        <div class="info-value">{{ $servis->urun_rengi ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Servis Detayları -->
            <div class="detail-card">
                <h3 class="section-title">🔧 Servis Detayları</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Öncelik Durumu</div>
                        <div class="info-value">{{ $servis->oncelik_durumu ?? 'Normal' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Arıza Tanımı</div>
                        <div class="info-value">{{ $servis->ariza_tanimi ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Personel</div>
                        <div class="info-value">{{ $servis->personel ?? '-' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tahmini Bitiş</div>
                        <div class="info-value">{{ $servis->tahmini_bitis_tarihi ? $servis->tahmini_bitis_tarihi->format('d.m.Y') : '-' }}</div>
                    </div>
                </div>
                
                @if($servis->musterinin_sikayeti)
                <div class="info-item" style="margin-top: 15px;">
                    <div class="info-label">Müşterinin Şikayeti</div>
                    <div class="info-value">{{ $servis->musterinin_sikayeti }}</div>
                </div>
                @endif

                @if($servis->teknisyenin_yorumu)
                <div class="info-item" style="margin-top: 15px;">
                    <div class="info-label">Teknisyenin Yorumu</div>
                    <div class="info-value">{{ $servis->teknisyenin_yorumu }}</div>
                </div>
                @endif
            </div>

            @if($servis->durum === 'Tamamlandı')
            <!-- Yapılan İşlemler -->
            @if($servis->urunler->count() > 0)
            <div class="detail-card">
                <h3 class="section-title">💰 Yapılan İşlemler ve Ücretler</h3>
                <table class="products-table">
                    <thead>
                        <tr>
                            <th>Ürün/Hizmet</th>
                            <th>Miktar</th>
                            <th>Birim</th>
                            <th>Birim Fiyat</th>
                            <th>KDV %</th>
                            <th>Toplam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servis->urunler as $urun)
                        <tr>
                            <td>{{ $urun->stok_adi }}</td>
                            <td>{{ $urun->miktar }}</td>
                            <td>{{ $urun->birim }}</td>
                            <td>{{ number_format($urun->birim_fiyat, 2) }} ₺</td>
                            <td>{{ $urun->kdv_orani }}%</td>
                            <td>{{ number_format($urun->toplam_kdv_dahil, 2) }} ₺</td>
                        </tr>
                        @endforeach
                        <tr style="font-weight: 600; background: #f8f9fa;">
                            <td colspan="5" style="text-align: right;">Genel Toplam:</td>
                            <td>{{ number_format($servis->vergiler_dahil_toplam, 2) }} ₺</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Servis Sonuç Bilgileri -->
            <div class="detail-card">
                <h3 class="section-title">✅ Servis Sonuç Bilgileri</h3>
                <div class="info-grid">
                    @if($servis->servis_sonucu)
                    <div class="info-item">
                        <div class="info-label">Servis Sonucu</div>
                        <div class="info-value">{{ $servis->servis_sonucu }}</div>
                    </div>
                    @endif
                    @if($servis->periyodik_bakim)
                    <div class="info-item">
                        <div class="info-label">Periyodik Bakım</div>
                        <div class="info-value">{{ $servis->periyodik_bakim }}</div>
                    </div>
                    @endif
                    @if($servis->bakim_tarihi)
                    <div class="info-item">
                        <div class="info-label">Bakım Tarihi</div>
                        <div class="info-value">{{ $servis->bakim_tarihi->format('d.m.Y') }}</div>
                    </div>
                    @endif
                    @if($servis->odeme_yontemi)
                    <div class="info-item">
                        <div class="info-label">Ödeme Yöntemi</div>
                        <div class="info-value">{{ $servis->odeme_yontemi }}</div>
                    </div>
                    @endif
                    @if($servis->islem_garantisi)
                    <div class="info-item">
                        <div class="info-label">İşlem Garantisi</div>
                        <div class="info-value">{{ $servis->islem_garantisi }}</div>
                    </div>
                    @endif
                    <div class="info-item">
                        <div class="info-label">SMS Hatırlatma</div>
                        <div class="info-value">{{ $servis->sms_hatirlatma ? 'Evet' : 'Hayır' }}</div>
                    </div>
                </div>
            </div>
            @endif
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#menuToggle').on('click', function() {
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
        });
    </script>
</body>
</html>

