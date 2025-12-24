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

                <div class="btn-group" style="display: flex; gap: 10px;">
                    @if($servis->durum !== 'Tamamlandı')
                    <a href="{{ route('servis.complete', $servis->id) }}" class="btn btn-primary">
                        ✅ Servisi Tamamla
                    </a>
                    @endif
                    
                    <button onclick="openPrintModal()" class="btn btn-secondary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fas fa-print"></i> Yazdır
                    </button>
                </div>
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

    <!-- Yazdır Modal -->
    <div id="printModal" class="print-modal">
        <div class="print-modal-content">
            <div class="print-modal-header">
                <h2><i class="fas fa-print"></i> Belge Yazdır</h2>
                <span class="print-modal-close" onclick="closePrintModal()">&times;</span>
            </div>
            <div class="print-modal-body">
                <p style="color: #666; margin-bottom: 25px;">Yazdırmak istediğiniz belgeyi seçin:</p>
                
                <div class="print-options">
                    <div class="print-option" onclick="printDocument('teslim')">
                        <div class="print-option-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="print-option-content">
                            <h3>Teslim Formu</h3>
                            <p>Cihaz teslim formu yazdır</p>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>

                    <div class="print-option" onclick="printDocument('kabul')">
                        <div class="print-option-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="print-option-content">
                            <h3>Kabul Formu</h3>
                            <p>Servis kabul formu yazdır</p>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>

                    <div class="print-option" onclick="printDocument('fis')">
                        <div class="print-option-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <div class="print-option-content">
                            <h3>Fiş</h3>
                            <p>Servis fişi yazdır</p>
                        </div>
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .print-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .print-modal-content {
            background-color: white;
            margin: 5% auto;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .print-modal-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .print-modal-header h2 {
            margin: 0;
            font-size: 22px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .print-modal-close {
            color: white;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .print-modal-close:hover {
            transform: rotate(90deg);
        }

        .print-modal-body {
            padding: 30px;
        }

        .print-options {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .print-option {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .print-option:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
        }

        .print-option-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .print-option-icon i {
            font-size: 28px;
            color: white;
        }

        .print-option-content {
            flex: 1;
        }

        .print-option-content h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #333;
        }

        .print-option-content p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .print-option > .fa-chevron-right {
            color: #667eea;
            font-size: 20px;
            opacity: 0;
            transition: all 0.3s;
        }

        .print-option:hover > .fa-chevron-right {
            opacity: 1;
            transform: translateX(5px);
        }

        .btn-secondary {
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
    </style>

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

        // Yazdır Modal Fonksiyonları
        function openPrintModal() {
            document.getElementById('printModal').style.display = 'block';
        }

        function closePrintModal() {
            document.getElementById('printModal').style.display = 'none';
        }

        function printDocument(type) {
            const servisId = {{ $servis->id }};
            let url = '';
            
            switch(type) {
                case 'teslim':
                    url = `/servis/${servisId}/pdf/teslim-formu`;
                    break;
                case 'kabul':
                    url = `/servis/${servisId}/pdf/kabul-formu`;
                    break;
                case 'fis':
                    url = `/servis/${servisId}/pdf/fis`;
                    break;
            }
            
            if(url) {
                window.open(url, '_blank');
            }
        }

        // Modal dışına tıklandığında kapat
        window.onclick = function(event) {
            const modal = document.getElementById('printModal');
            if (event.target == modal) {
                closePrintModal();
            }
        }
    </script>
</body>
</html>

