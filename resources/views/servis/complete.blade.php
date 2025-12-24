<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Servis Tamamla - {{ $servis->servis_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stepper.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-row {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border: 1px solid #e0e0e0;
        }
        .product-row-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .btn-remove-product {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-add-product {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        .summary-table {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .summary-row.total {
            font-weight: 700;
            font-size: 18px;
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
        }
        .readonly-info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 10px;
        }
        .info-item {
            font-size: 14px;
        }
        .info-item strong {
            display: block;
            color: #666;
            font-size: 12px;
            margin-bottom: 3px;
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
                <h2>✅ Servis Tamamlama - {{ $servis->servis_no }}</h2>
            </div>

            <div class="alert alert-error" id="alertBox" style="display: none;"></div>

            <form id="completeForm" action="{{ route('servis.finish', $servis->id) }}" method="POST">
                @csrf
                
                <div class="stepper-container">
                    <!-- Stepper Header -->
                    <div class="stepper-header">
                        <div class="step" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Servis Bilgileri</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Yapılan İşlemler</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Ücret Özeti</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Servis Sonucu</div>
                        </div>
                    </div>

                    <!-- Stepper Body -->
                    <div class="stepper-body">
                        <!-- Step 1: Servis Bilgileri (Readonly) -->
                        <div id="step1" class="step-content">
                            <h3 class="section-title">📋 Mevcut Servis Bilgileri</h3>
                            
                            <div class="readonly-info">
                                <h4>Müşteri Bilgileri</h4>
                                <div class="info-row">
                                    <div class="info-item">
                                        <strong>Cari Hesap:</strong>
                                        {{ $servis->cariHesap->cari_hesap_adi }}
                                    </div>
                                    <div class="info-item">
                                        <strong>GSM:</strong>
                                        {{ $servis->gsm ?? '-' }}
                                    </div>
                                    <div class="info-item">
                                        <strong>E-posta:</strong>
                                        {{ $servis->eposta ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="readonly-info">
                                <h4>Ürün Bilgileri</h4>
                                <div class="info-row">
                                    <div class="info-item">
                                        <strong>Marka/Model:</strong>
                                        {{ $servis->marka }} {{ $servis->model }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Seri No:</strong>
                                        {{ $servis->seri_numarasi ?? '-' }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Garanti:</strong>
                                        {{ $servis->garanti_durumu ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <div class="readonly-info">
                                <h4>Arıza Bilgileri</h4>
                                <div class="info-row">
                                    <div class="info-item">
                                        <strong>Arıza Tanımı:</strong>
                                        {{ $servis->ariza_tanimi ?? '-' }}
                                    </div>
                                    <div class="info-item">
                                        <strong>Öncelik:</strong>
                                        {{ $servis->oncelik_durumu ?? 'Normal' }}
                                    </div>
                                </div>
                                @if($servis->musterinin_sikayeti)
                                <div style="margin-top: 10px;">
                                    <strong>Müşteri Şikayeti:</strong>
                                    <p>{{ $servis->musterinin_sikayeti }}</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Step 2: Yapılan İşlemler -->
                        <div id="step2" class="step-content">
                            <h3 class="section-title">💰 Yapılan İşlemler</h3>
                            
                            <div class="form-row single-column">
                                <div class="form-group">
                                    <label>İşlem Açıklaması</label>
                                    <textarea name="yapilan_islemler" class="form-control" rows="3" placeholder="Yapılan işlemlerin detaylı açıklaması..."></textarea>
                                </div>
                            </div>

                            <h4 style="margin: 20px 0 10px;">Ürün/Hizmet Kalemleri</h4>
                            <div id="productList">
                                <!-- Ürünler buraya eklenecek -->
                            </div>
                            <button type="button" class="btn-add-product" id="btnAddProduct">
                                ➕ Ürün/Hizmet Ekle
                            </button>
                        </div>

                        <!-- Step 3: Ücret Özeti -->
                        <div id="step3" class="step-content">
                            <h3 class="section-title">💵 Ücret Özeti</h3>
                            
                            <div class="alert alert-info" style="margin-bottom: 20px; padding: 12px; background: #e3f2fd; border-left: 4px solid #2196f3; font-size: 14px;">
                                ℹ️ Otomatik hesaplanan tutarları manuel olarak değiştirebilirsiniz.
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>Mal Hizmet Tutarı (KDV Hariç)</label>
                                    <input type="number" name="toplam_mal_hizmet_tutari" id="inputSubtotal" class="form-control manual-price" step="0.01" min="0" value="0.00">
                                </div>
                                <div class="form-group">
                                    <label>İskonto Tutarı</label>
                                    <input type="number" name="toplam_iskonto" id="inputDiscount" class="form-control manual-price" step="0.01" min="0" value="0.00">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label>KDV Tutarı</label>
                                    <input type="number" name="hesaplanan_kdv" id="inputKdv" class="form-control manual-price" step="0.01" min="0" value="0.00">
                                </div>
                                <div class="form-group">
                                    <label><strong>Toplam Tutar (KDV Dahil)</strong></label>
                                    <input type="number" name="vergiler_dahil_toplam" id="inputTotal" class="form-control manual-price" step="0.01" min="0" value="0.00" style="font-weight: 700; font-size: 18px; color: #2e7d32;">
                                </div>
                            </div>

                            <div style="margin-top: 15px;">
                                <button type="button" class="btn-add-product" id="btnRecalculate">
                                    🔄 Otomatik Hesapla
                                </button>
                            </div>
                        </div>

                        <!-- Step 4: Servis Sonucu -->
                        <div id="step4" class="step-content">
                            <h3 class="section-title">✅ Servis Sonucu ve Bilgilendirme</h3>
                            
                            <div class="form-row single-column">
                                <div class="form-group">
                                    <label>Teknisyenin Yorumu</label>
                                    <textarea name="servis_sonucu" class="form-control" rows="3" placeholder="Servis sonucuna dair açıklama..."></textarea>
                                </div>
                            </div>

                            @if($mevcutBakim)
                            <!-- Mevcut Bakım Uyarısı -->
                            <div class="alert alert-info" style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-info-circle" style="font-size: 24px; color: #2196f3;"></i>
                                    <div>
                                        <strong>Bu müşteri için zaten aktif bakım takibi bulunmaktadır</strong>
                                        <div style="margin-top: 5px; color: #0d47a1;">
                                            <strong>Periyot:</strong> {{ $mevcutBakim->periyodik_bakim }}<br>
                                            <strong>Bakım Tarihi:</strong> {{ $mevcutBakim->bakim_tarihi ? $mevcutBakim->bakim_tarihi->format('d.m.Y') : '-' }}<br>
                                            <strong>Servis No:</strong> {{ $mevcutBakim->servis_no }}
                                        </div>
                                        <div style="margin-top: 10px;">
                                            <a href="{{ route('bakim.show', $mevcutBakim->id) }}" target="_blank" class="btn btn-sm btn-primary" style="padding: 5px 12px; font-size: 13px;">
                                                <i class="fas fa-external-link-alt"></i> Bakım Detayını Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Periyodik Bakım Seçenekleri -->
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Periyodik Bakım</label>
                                    <select name="periyodik_bakim" class="form-control" id="periyodikBakim">
                                        <option value="">Seçiniz...</option>
                                        <option value="1 Aylık">1 Aylık</option>
                                        <option value="2 Aylık">2 Aylık</option>
                                        <option value="3 Aylık">3 Aylık</option>
                                        <option value="4 Aylık">4 Aylık</option>
                                        <option value="5 Aylık">5 Aylık</option>
                                        <option value="6 Aylık">6 Aylık</option>
                                        <option value="7 Aylık">7 Aylık</option>
                                        <option value="8 Aylık">8 Aylık</option>
                                        <option value="9 Aylık">9 Aylık</option>
                                        <option value="10 Aylık">10 Aylık</option>
                                        <option value="11 Aylık">11 Aylık</option>
                                        <option value="12 Aylık">12 Aylık (Yıllık)</option>
                                        <option value="13 Aylık">13 Aylık</option>
                                        <option value="14 Aylık">14 Aylık</option>
                                        <option value="15 Aylık">15 Aylık</option>
                                        <option value="16 Aylık">16 Aylık</option>
                                        <option value="17 Aylık">17 Aylık</option>
                                        <option value="18 Aylık">18 Aylık</option>
                                        <option value="19 Aylık">19 Aylık</option>
                                        <option value="20 Aylık">20 Aylık</option>
                                        <option value="21 Aylık">21 Aylık</option>
                                        <option value="22 Aylık">22 Aylık</option>
                                        <option value="23 Aylık">23 Aylık</option>
                                        <option value="24 Aylık">24 Aylık (2 Yıllık)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Bakım Tarihi</label>
                                    <input type="date" name="bakim_tarihi" class="form-control" id="bakimTarihi">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                        <input type="checkbox" name="sms_hatirlatma" value="1" style="width: auto; cursor: pointer;">
                                        <span>SMS Hatırlatma Gönder</span>
                                    </label>
                                </div>
                            </div>
                            @endif

                            <div class="form-row">
                                <div class="form-group">
                                    <label>İşlem Garantisi</label>
                                    <input type="text" name="islem_garantisi" class="form-control" placeholder="Örn: 1 Yıl, 6 Ay, vs.">
                                </div>
                                <div class="form-group">
                                    <label>Ödeme Yöntemi</label>
                                    <select name="odeme_yontemi" class="form-control">
                                        <option value="">Seçiniz...</option>
                                        <option value="Nakit">Nakit</option>
                                        <option value="Kredi Kartı">Kredi Kartı</option>
                                        <option value="EFT/Havale">EFT/Havale</option>
                                        <option value="Çek">Çek</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step Actions -->
                        <div class="step-actions">
                            <button type="button" class="btn btn-prev" id="btnPrev" style="display: none;">⬅ Geri</button>
                            <div style="flex: 1;"></div>
                            <button type="button" class="btn btn-next" id="btnNext">İleri ➡</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit" style="display: none;">✅ Servisi Bitir</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/servis-complete.js') }}"></script>
</body>
</html>

