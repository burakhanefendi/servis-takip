<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teklif - {{ $teklif->teklif_no }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .container {
                padding: 0;
            }
            @page {
                size: A4;
                margin: 15mm;
            }
            .print-button {
                display: none !important;
            }
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #5568d3;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .container {
            padding: 20px;
        }
        
        /* Header */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 20px;
        }
        
        .header-left {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }
        
        .header-right {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: right;
        }
        
        .logo {
            max-width: 150px;
            max-height: 60px;
            margin-bottom: 10px;
            display: block;
            object-fit: contain;
        }
        
        .logo-placeholder {
            width: 80px;
            height: 60px;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .company-info {
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }
        
        .company-name {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .document-title {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .document-no {
            font-size: 14px;
            color: #555;
        }
        
        /* Info Section */
        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-left {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 15px;
        }
        
        .info-box-title {
            font-size: 12px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-item {
            margin-bottom: 5px;
            font-size: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 110px;
        }
        
        .info-value {
            color: #333;
        }
        
        /* Table */
        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .product-table th {
            background: #667eea;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #5568d3;
        }
        
        .product-table td {
            padding: 8px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }
        
        .product-table tr:nth-child(even) {
            background: #f8fafc;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* Summary */
        .summary-section {
            float: right;
            width: 300px;
            margin-top: 10px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .summary-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 10px;
        }
        
        .summary-label {
            font-weight: bold;
            color: #555;
        }
        
        .summary-value {
            text-align: right;
            font-weight: bold;
            color: #333;
        }
        
        .summary-total {
            background: #667eea;
            color: white !important;
            font-size: 12px;
            font-weight: bold;
        }
        
        /* Notes */
        .notes-section {
            clear: both;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        
        .notes-title {
            font-size: 12px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .notes-content {
            font-size: 10px;
            color: #555;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 5px;
        }
        
        .status-taslak {
            background: #fef3c7;
            color: #92400e;
        }
        
        .status-gonderildi {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .status-onaylandi {
            background: #d1fae5;
            color: #065f46;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        🖨️ Yazdır / PDF Olarak Kaydet
    </button>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                @if($settings && $settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="logo">
                @else
                    <svg class="logo-placeholder" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                @endif
                <div class="company-info">
                    <div class="company-name">{{ $settings->firma_adi }}</div>
                    <div>{{ $settings->adres }} {{ $settings->ilce }}/{{ $settings->il }}</div>
                    <div>Tel: {{ $settings->telefon_1 }} @if($settings->telefon_2) / {{ $settings->telefon_2 }}@endif</div>
                    <div>E-posta: {{ $settings->email }}</div>
                    @if($settings->website)
                    <div>Web: {{ $settings->website }}</div>
                    @endif
                </div>
            </div>
            <div class="header-right">
                <div class="document-title">TEKLİF</div>
                <div class="document-no">{{ $teklif->teklif_no }}</div>
                @if($teklif->durum == 'taslak')
                    <div class="status-badge status-taslak">TASLAK</div>
                @elseif($teklif->durum == 'gonderildi')
                    <div class="status-badge status-gonderildi">GÖNDERİLDİ</div>
                @elseif($teklif->durum == 'onaylandi')
                    <div class="status-badge status-onaylandi">ONAYLANDI</div>
                @endif
            </div>
        </div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-box">
                    <div class="info-box-title">MÜŞTERİ BİLGİLERİ</div>
                    <div class="info-item">
                        <span class="info-label">Cari Adı:</span>
                        <span class="info-value">{{ $teklif->cariHesap->cari_hesap_adi }}</span>
                    </div>
                    @if($teklif->cariHesap->musteri_kodu)
                    <div class="info-item">
                        <span class="info-label">Müşteri Kodu:</span>
                        <span class="info-value">{{ $teklif->cariHesap->musteri_kodu }}</span>
                    </div>
                    @endif
                    @if($teklif->cariHesap->gsm)
                    <div class="info-item">
                        <span class="info-label">GSM:</span>
                        <span class="info-value">{{ $teklif->cariHesap->gsm }}</span>
                    </div>
                    @endif
                    @if($teklif->cariHesap->sabit_telefon)
                    <div class="info-item">
                        <span class="info-label">Telefon:</span>
                        <span class="info-value">{{ $teklif->cariHesap->sabit_telefon }}</span>
                    </div>
                    @endif
                    @if($teklif->cariHesap->eposta)
                    <div class="info-item">
                        <span class="info-label">E-posta:</span>
                        <span class="info-value">{{ $teklif->cariHesap->eposta }}</span>
                    </div>
                    @endif
                    @if($teklif->cariHesap->adres)
                    <div class="info-item">
                        <span class="info-label">Adres:</span>
                        <span class="info-value">{{ $teklif->cariHesap->adres }} {{ $teklif->cariHesap->ilce }}/{{ $teklif->cariHesap->il }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="info-right">
                <div class="info-box">
                    <div class="info-box-title">TEKLİF BİLGİLERİ</div>
                    @if($teklif->baslik)
                    <div class="info-item">
                        <span class="info-label">Teklif Başlığı:</span>
                        <span class="info-value">{{ $teklif->baslik }}</span>
                    </div>
                    @endif
                    <div class="info-item">
                        <span class="info-label">Teklif Tarihi:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($teklif->baslangic_tarihi)->format('d.m.Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Geçerlilik Tarihi:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($teklif->gecerlilik_tarihi)->format('d.m.Y') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Para Birimi:</span>
                        <span class="info-value">{{ $teklif->para_birimi }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Products Table -->
        @if($teklif->urunler->count() > 0)
        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 35%;">ÜRÜN/HİZMET</th>
                    <th style="width: 10%;" class="text-center">MİKTAR</th>
                    <th style="width: 12%;" class="text-right">BİRİM FİYAT</th>
                    <th style="width: 8%;" class="text-center">KDV %</th>
                    <th style="width: 15%;" class="text-right">TUTAR<br>(KDV HARİÇ)</th>
                    <th style="width: 15%;" class="text-right">TUTAR<br>(KDV DAHİL)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teklif->urunler->sortBy('sira_no') as $index => $urun)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $urun->stok_tanimi }}</strong>
                        @if($urun->sku)
                        <br><small style="color: #999;">SKU: {{ $urun->sku }}</small>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($urun->miktar, 2) }} {{ $urun->birim }}</td>
                    <td class="text-right">
                        {{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->birim_fiyat, 2) }}
                    </td>
                    <td class="text-center">{{ number_format($urun->kdv_oran, 0) }}%</td>
                    <td class="text-right">
                        {{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->tutar_kdv_haric, 2) }}
                    </td>
                    <td class="text-right">
                        <strong>{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->tutar_kdv_dahil, 2) }}</strong>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Summary -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="summary-label">Mal/Hizmet Tutarı:</td>
                    <td class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->mal_hizmet_tutari, 2) }}</td>
                </tr>
                @if($teklif->toplam_iskonto > 0)
                <tr>
                    <td class="summary-label">Toplam İskonto:</td>
                    <td class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->toplam_iskonto, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="summary-label">Ara Toplam:</td>
                    <td class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->ara_toplam, 2) }}</td>
                </tr>
                <tr>
                    <td class="summary-label">Hesaplanan KDV:</td>
                    <td class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->hesaplanan_kdv, 2) }}</td>
                </tr>
                <tr class="summary-total">
                    <td class="summary-label">GENEL TOPLAM:</td>
                    <td class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->genel_toplam, 2) }}</td>
                </tr>
            </table>
        </div>
        @endif
        
        <!-- Notes -->
        @if($teklif->notlar)
        <div class="notes-section">
            <div class="notes-title">TEKLİF İÇERİĞİ VE KOŞULLARI</div>
            <div class="notes-content">{{ $teklif->notlar }}</div>
        </div>
        @endif
        
        <!-- Footer -->
        <div class="footer">
            <p>Bu teklif {{ \Carbon\Carbon::parse($teklif->gecerlilik_tarihi)->format('d.m.Y') }} tarihine kadar geçerlidir.</p>
            <p>{{ $settings->firma_adi }} - {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>

