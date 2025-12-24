<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Teslim Formu - {{ $servis->servis_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: A4;
            margin: 15mm;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.4;
        }
        
        .container {
            max-width: 210mm;
            margin: 0 auto;
        }
        
        /* Başlık */
        .main-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            color: #000;
            padding: 15px 0;
            border-top: 3px solid #4a5fa5;
            border-bottom: 3px solid #4a5fa5;
            margin-bottom: 20px;
        }
        
        /* Üst Bilgi Bölümü */
        .top-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .company-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }
        
        .company-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 10px;
        }
        
        .company-name {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 5px;
        }
        
        .company-details {
            font-size: 9pt;
            line-height: 1.6;
        }
        
        .service-info {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        
        .service-info div {
            margin-bottom: 4px;
            font-size: 10pt;
        }
        
        .service-info strong {
            font-weight: bold;
        }
        
        /* Tablolar */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .section-header {
            background-color: #4a5fa5;
            color: white;
            font-weight: bold;
            padding: 8px;
            text-align: left;
            font-size: 10pt;
        }
        
        td {
            border: 1px solid #ccc;
            padding: 6px;
            font-size: 9pt;
        }
        
        .label-cell {
            background-color: #e8eaf6;
            font-weight: bold;
            width: 20%;
        }
        
        .value-cell {
            width: 30%;
        }
        
        /* Ürünler Tablosu */
        .products-table th {
            background-color: #e8eaf6;
            font-weight: bold;
            padding: 6px;
            border: 1px solid #ccc;
            font-size: 8pt;
            text-align: center;
        }
        
        .products-table td {
            text-align: center;
            font-size: 8pt;
            padding: 4px;
        }
        
        .empty-row td {
            text-align: center;
            color: #999;
            font-style: italic;
        }
        
        /* Ödeme Özeti */
        .payment-summary {
            margin-top: 10px;
        }
        
        .payment-summary td {
            padding: 8px;
        }
        
        .payment-label {
            background-color: #e8eaf6;
            font-weight: bold;
            text-align: left;
        }
        
        .payment-value {
            text-align: right;
            font-weight: bold;
        }
        
        .total-row {
            background-color: #4a5fa5 !important;
            color: white !important;
            font-size: 11pt;
        }
        
        /* İmza Bölümü */
        .signature-section {
            margin-top: 15px;
        }
        
        .signature-box {
            height: 80px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
            color: #999;
            font-size: 9pt;
        }
        
        /* Alt Bilgi */
        .footer {
            text-align: center;
            margin-top: 20px;
        }
        
        .qr-code {
            width: 60px;
            height: 60px;
            margin: 0 auto 10px;
        }
        
        .software-info {
            font-size: 9pt;
            color: #4a5fa5;
        }
        
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Başlık -->
        <div class="main-title">SERVİS TESLİM FORMU</div>
        
        <!-- Üst Bilgi Bölümü -->
        <div class="top-section">
            <div class="company-info">
                <div style="display: flex; align-items: flex-start; gap: 15px;">
                    @if($settings && $settings->logo)
                        <img src="{{ public_path('storage/' . $settings->logo) }}" class="company-logo" alt="Logo" style="width: 60px; height: 60px; object-fit: contain;">
                    @else
                        <svg class="company-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <ellipse cx="50" cy="65" rx="25" ry="30" fill="#3498db"/>
                            <ellipse cx="50" cy="35" rx="15" ry="20" fill="#5dade2"/>
                        </svg>
                    @endif
                    <div>
                        <div class="company-name">{{ $settings->firma_adi ?? 'KAPAKLI SAĞLIKLI SU ARITMA' }}</div>
                        <div class="company-details">
                            @if($settings->adres)
                            <strong>Adres:</strong> {{ $settings->adres }}<br>
                            {{ $settings->ilce ?? '' }}{{ $settings->ilce && $settings->il ? ' ' : '' }}{{ $settings->il ?? '' }}<br>
                            @endif
                            @if($settings->telefon_1 || $settings->telefon_2)
                            <strong>Telefon:</strong> {{ $settings->telefon_1 ?? '' }} {{ $settings->telefon_2 ?? '' }}<br>
                            @endif
                            @if($settings->email)
                            <strong>E-Posta:</strong> {{ $settings->email }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="service-info">
                <div><strong>Servis Numarası:</strong> {{ $servis->servis_no }}</div>
                <div><strong>Teslim Tarihi:</strong> {{ $servis->tamamlanma_tarihi ? \Carbon\Carbon::parse($servis->tamamlanma_tarihi)->format('d.m.Y') : '-' }}</div>
                <div><strong>İlgili Personel:</strong> {{ $servis->personel ?? '-' }}</div>
                <div><strong>Teslimat Türü:</strong> {{ $servis->teslimat_turu ?? 'Elden' }}</div>
            </div>
        </div>
        
        <!-- Müşteri Bilgileri -->
        <table>
            <tr>
                <td colspan="4" class="section-header">Müşteri Bilgileri</td>
            </tr>
            <tr>
                <td class="label-cell">Müşteri Adı</td>
                <td class="value-cell">{{ $servis->cariHesap->cari_hesap_adi }}</td>
                <td class="label-cell">Teslim Alan</td>
                <td class="value-cell">-</td>
            </tr>
            <tr>
                <td class="label-cell">GSM</td>
                <td class="value-cell">{{ $servis->gsm ?? $servis->cariHesap->gsm ?? '-' }}</td>
                <td class="label-cell">E-Posta</td>
                <td class="value-cell">{{ $servis->eposta ?? $servis->cariHesap->eposta ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Adres</td>
                <td colspan="3" class="value-cell">{{ $servis->adres ?? ($servis->cariHesap->adres . ' - ' . $servis->cariHesap->ilce . '/' . $servis->cariHesap->il) ?? '-' }}</td>
            </tr>
        </table>
        
        <!-- Ürün Bilgileri -->
        <table>
            <tr>
                <td colspan="4" class="section-header">Ürün Bilgileri</td>
            </tr>
            <tr>
                <td class="label-cell">Marka</td>
                <td class="value-cell">{{ $servis->marka ?? '-' }}</td>
                <td class="label-cell">Model</td>
                <td class="value-cell">{{ $servis->model ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Ürün Cinsi</td>
                <td class="value-cell">{{ $servis->urun_cinsi ?? '-' }}</td>
                <td class="label-cell">Garanti</td>
                <td class="value-cell">{{ $servis->garanti_durumu ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Seri No</td>
                <td class="value-cell">{{ $servis->seri_numarasi ?? '-' }}</td>
                <td class="label-cell">Aksesuarlar</td>
                <td class="value-cell">{{ $servis->urun_rengi ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Fiziksel Durumu</td>
                <td colspan="3" class="value-cell">{{ $servis->urunun_fiziksel_durumu ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Müşteri Şikayeti</td>
                <td colspan="3" class="value-cell">{{ $servis->musterinin_sikayeti ?? '-' }}</td>
            </tr>
        </table>
        
        <!-- Yapılan İşlemler -->
        <table>
            <tr>
                <td class="section-header">Yapılan İşlemler</td>
            </tr>
            <tr>
                <td style="min-height: 60px; padding: 10px;">{{ $servis->yapilan_islemler ?? '-' }}</td>
            </tr>
        </table>
        
        <!-- Ödeme Şekli -->
        <table>
            <tr>
                <td class="section-header">Ödeme Şekli</td>
            </tr>
            <tr>
                <td style="padding: 10px;">{{ $servis->odeme_yontemi ?? '-' }}</td>
            </tr>
        </table>
        
        <!-- Ürünler ve Hizmetler -->
        <table class="products-table">
            <tr>
                <td colspan="9" class="section-header">Ürünler ve Hizmetler</td>
            </tr>
            <tr>
                <th>Stok Kodu</th>
                <th>Stok Tanımı</th>
                <th>Miktar</th>
                <th>Birim Fiyat</th>
                <th>İndirim</th>
                <th>Oran</th>
                <th>KDV</th>
                <th>Tutar</th>
                <th>Toplam</th>
            </tr>
            @if($servis->urunler && $servis->urunler->count() > 0)
                @foreach($servis->urunler as $urun)
                <tr>
                    <td>{{ $urun->stok_kodu ?? '-' }}</td>
                    <td>{{ $urun->stok_adi }}</td>
                    <td>{{ $urun->miktar }} {{ $urun->birim }}</td>
                    <td>{{ $urun->para_birimi }} {{ number_format($urun->birim_fiyat, 2, ',', '.') }}</td>
                    <td>-</td>
                    <td>%{{ $urun->kdv_orani }}</td>
                    <td>{{ number_format($urun->kdv_tutari, 2, ',', '.') }}</td>
                    <td>{{ number_format($urun->toplam_kdv_haric, 2, ',', '.') }}</td>
                    <td>{{ number_format($urun->toplam_kdv_dahil, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            @else
            <tr class="empty-row">
                <td colspan="9">Ürün veya hizmet eklenmedi.</td>
            </tr>
            @endif
        </table>
        
        <!-- Ödeme Özeti -->
        <table class="payment-summary">
            <tr>
                <td colspan="2" class="section-header">Ödeme Özeti</td>
            </tr>
            <tr>
                <td class="payment-label">Mal Hizmet Tutarı</td>
                <td class="payment-value">₺{{ number_format($servis->toplam_mal_hizmet_tutari ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="payment-label">Toplam İskonto</td>
                <td class="payment-value">₺{{ number_format($servis->toplam_iskonto ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="payment-label">Hesaplanan KDV</td>
                <td class="payment-value">₺{{ number_format($servis->hesaplanan_kdv ?? 0, 2, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td class="payment-label total-row">Toplam Tutar</td>
                <td class="payment-value total-row">₺{{ number_format($servis->vergiler_dahil_toplam ?? 0, 2, ',', '.') }}</td>
            </tr>
        </table>
        
        <!-- İmzalar -->
        <table class="signature-section">
            <tr>
                <td colspan="2" class="section-header">İmzalar</td>
            </tr>
            <tr>
                <td class="label-cell" style="width: 50%; text-align: center;">Müşteri Adı Soyadı ve İmzası</td>
                <td class="label-cell" style="width: 50%; text-align: center;">{{ $settings->firma_adi ?? 'KAPAKLI SAĞLIKLI SU ARITMA' }}</td>
            </tr>
            <tr>
                <td class="signature-box" style="border-right: 1px solid #ccc;">Ad Soyad, Kaşe, İmza</td>
                <td class="signature-box">Ad Soyad, Kaşe, İmza</td>
            </tr>
        </table>
        
        <!-- Servis Notları -->
        <table>
            <tr>
                <td class="section-header">Servis Notları</td>
            </tr>
            <tr>
                <td style="min-height: 50px; padding: 10px;">{{ $servis->teknisyenin_yorumu ?? '' }}</td>
            </tr>
        </table>
        
        <!-- Alt Bilgi -->
        <div class="footer">
            <div class="qr-code">
                <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <rect width="100" height="100" fill="white"/>
                    <rect x="10" y="10" width="10" height="10" fill="black"/>
                    <rect x="30" y="10" width="10" height="10" fill="black"/>
                    <rect x="50" y="10" width="10" height="10" fill="black"/>
                    <rect x="70" y="10" width="10" height="10" fill="black"/>
                    <rect x="10" y="30" width="10" height="10" fill="black"/>
                    <rect x="50" y="30" width="10" height="10" fill="black"/>
                    <rect x="70" y="30" width="10" height="10" fill="black"/>
                    <rect x="10" y="50" width="10" height="10" fill="black"/>
                    <rect x="30" y="50" width="10" height="10" fill="black"/>
                    <rect x="50" y="50" width="10" height="10" fill="black"/>
                    <rect x="70" y="50" width="10" height="10" fill="black"/>
                    <rect x="10" y="70" width="10" height="10" fill="black"/>
                    <rect x="30" y="70" width="10" height="10" fill="black"/>
                    <rect x="50" y="70" width="10" height="10" fill="black"/>
                    <rect x="70" y="70" width="10" height="10" fill="black"/>
                </svg>
            </div>
            <div class="software-info">

                ArıtmApp ile üretildi
            </div>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

