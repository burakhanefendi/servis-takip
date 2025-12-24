<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servis Kabul Formu - {{ $servis->servis_no }}</title>
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
            font-size: 11pt;
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
            font-size: 11pt;
        }
        
        td {
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 10pt;
        }
        
        .label-cell {
            background-color: #e8eaf6;
            font-weight: bold;
            width: 25%;
        }
        
        .value-cell {
            width: 25%;
        }
        
        /* İmza Bölümü */
        .signature-section {
            margin-top: 20px;
        }
        
        .signature-boxes {
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            height: 100px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
            color: #999;
            font-size: 10pt;
        }
        
        /* Notlar Bölümü */
        .notes-section {
            min-height: 80px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        
        /* Alt Bilgi */
        .footer {
            text-align: center;
            margin-top: 30px;
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
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Başlık -->
        <div class="main-title">SERVİS KABUL FORMU</div>
        
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
                            {{ $settings->ilce ?? '' }}{{ $settings->ilce && $settings->il ? '/' : '' }}{{ $settings->il ?? '' }}<br>
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
                <div><strong>Teslim Tarihi:</strong> {{ $servis->created_at ? $servis->created_at->format('d.m.Y') : '-' }}</div>
                <div><strong>Tahmini Bitiş Tarihi:</strong> {{ $servis->tahmini_bitis_tarihi ? \Carbon\Carbon::parse($servis->tahmini_bitis_tarihi)->format('d.m.Y') : '-' }}</div>
                <div><strong>Teslim Alan Personel:</strong> {{ $servis->personel ?? '-' }}</div>
                <div><strong>Teslimat Türü:</strong> {{ $servis->teslimat_turu ?? 'Elden' }}</div>
            </div>
        </div>
        
        <!-- Müşteri Bilgileri -->
        <table>
            <tr>
                <td colspan="4" class="section-header">Müşteri Bilgileri</td>
            </tr>
            <tr>
                <td class="label-cell">Ürün Teslim Eden Kişi</td>
                <td class="value-cell">{{ $servis->cariHesap->yetkili_kisi ?? $servis->cariHesap->cari_hesap_adi }}</td>
                <td class="label-cell">Teslim Alan</td>
                <td class="value-cell">{{ $servis->personel ?? '-' }}</td>
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
                <td class="label-cell">Şikayet/Arıza</td>
                <td colspan="3" class="value-cell">{{ $servis->musterinin_sikayeti ?? $servis->ariza_tanimi ?? '-' }}</td>
            </tr>
        </table>
        
        <!-- İmzalar -->
        <table class="signature-section">
            <tr>
                <td colspan="2" class="section-header">İmzalar</td>
            </tr>
            <tr>
                <td class="label-cell" style="width: 100%; text-align: center;">Müşteri Adı Soyadı ve İmzası</td>
                <td class="label-cell" style="width: 100%; text-align: center;">{{ $settings->firma_adi ?? 'KAPAKLI SAĞLIKLI SU ARITMA' }}</td>
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
                <td class="notes-section">{{ $servis->teknisyenin_yorumu ?? '' }}</td>
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

