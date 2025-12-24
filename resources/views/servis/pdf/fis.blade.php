<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=285px, initial-scale=1.0">
    <title>Servis Fişi - {{ $servis->servis_no }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            size: 285px 600px;
            margin: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            line-height: 1.3;
            width: 285px;
            padding: 15px;
            background: white;
        }
        
        /* Logo ve Başlık */
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        
        .logo-container {
            margin-bottom: 8px;
        }
        
        .company-logo {
            width: 50px;
            height: 50px;
            margin: 0 auto;
        }
        
        .company-name {
            font-size: 12pt;
            font-weight: bold;
            color: #3498db;
            margin-top: 5px;
        }
        
        .company-info {
            font-size: 7pt;
            color: #333;
            line-height: 1.4;
            margin-top: 8px;
        }
        
        .company-info div {
            margin: 2px 0;
        }
        
        /* Bölüm Başlıkları */
        .section-header {
            background-color: #000;
            color: white;
            padding: 5px 8px;
            font-weight: bold;
            font-size: 9pt;
            margin: 10px 0 5px 0;
            text-align: center;
        }
        
        /* Bilgi Satırları */
        .info-table {
            width: 100%;
            margin-bottom: 8px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            font-size: 8pt;
            border-bottom: 1px dashed #ddd;
        }
        
        .info-label {
            font-weight: bold;
            color: #333;
        }
        
        .info-value {
            color: #000;
            text-align: right;
            max-width: 60%;
            word-wrap: break-word;
        }
        
        /* Uzun Metinler */
        .full-width-value {
            padding: 5px 0;
            font-size: 8pt;
            color: #000;
            word-wrap: break-word;
            border-bottom: 1px dashed #ddd;
            margin-bottom: 3px;
        }
        
        /* Alt Bilgi */
        .footer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 7pt;
            color: #666;
        }
        
        /* Termal Yazıcı için */
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Başlık -->
    <div class="header">
        <div class="logo-container">
            @if($settings && $settings->logo)
                <img src="{{ public_path('storage/' . $settings->logo) }}" class="company-logo" alt="Logo" style="width: 50px; height: 50px; object-fit: contain; margin: 0 auto; display: block;">
            @else
                <svg class="company-logo" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <ellipse cx="50" cy="65" rx="25" ry="30" fill="#3498db"/>
                    <ellipse cx="50" cy="35" rx="15" ry="20" fill="#5dade2"/>
                </svg>
            @endif
        </div>
        <div class="company-name">{{ $settings->firma_adi ?? 'SAĞLIKLI SU ARITMA' }}</div>
        <div class="company-info">
            <div><strong>{{ $settings->firma_adi ?? 'KAPAKLI SAĞLIKLI SU ARITMA' }}</strong></div>
            @if($settings->telefon_1 || $settings->telefon_2)
            <div>{{ $settings->telefon_1 ?? '' }}{{ $settings->telefon_1 && $settings->telefon_2 ? ' - ' : '' }}{{ $settings->telefon_2 ?? '' }}</div>
            @endif
            @if($settings->email)
            <div>{{ $settings->email }}</div>
            @endif
            @if($settings->adres)
            <div>{{ $settings->adres }}</div>
            @endif
            @if($settings->il || $settings->ilce)
            <div>{{ $settings->il ?? '' }}{{ $settings->il && $settings->ilce ? ' - ' : '' }}{{ $settings->ilce ?? '' }}</div>
            @endif
        </div>
    </div>
    
    <!-- Servis Bilgileri -->
    <div class="section-header">SERVİS BİLGİLERİ</div>
    <div class="info-table">
        <div class="info-row">
            <span class="info-label">Servis No</span>
            <span class="info-value">{{ $servis->servis_no }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tarih</span>
            <span class="info-value">{{ $servis->created_at ? $servis->created_at->format('d.m.Y H:i') : '-' }}</span>
        </div>
    </div>
    
    <!-- Müşteri Bilgileri -->
    <div class="section-header">MÜŞTERİ BİLGİLERİ</div>
    <div class="info-table">
        <div class="info-row">
            <span class="info-label">Ad Soyad</span>
            <span class="info-value">{{ $servis->cariHesap->cari_hesap_adi }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Telefon</span>
            <span class="info-value">{{ $servis->gsm ?? $servis->cariHesap->gsm ?? '-' }}</span>
        </div>
    </div>
    <div class="full-width-value">
        <strong>Adres:</strong><br>
        {{ $servis->adres ?? ($servis->cariHesap->adres . ' - ' . $servis->cariHesap->ilce . '/' . $servis->cariHesap->il) ?? '-' }}
    </div>
    
    <!-- Ürün Bilgileri -->
    <div class="section-header">ÜRÜN BİLGİLERİ</div>
    <div class="info-table">
        <div class="info-row">
            <span class="info-label">Marka/Model</span>
            <span class="info-value">{{ $servis->marka ?? '-' }} {{ $servis->model ? '- ' . $servis->model : '' }}</span>
        </div>
    </div>
    <div class="full-width-value">
        <strong>Müşteri Şikayeti:</strong><br>
        {{ $servis->musterinin_sikayeti ?? $servis->ariza_tanimi ?? '-' }}
    </div>
    <div class="info-table">
        <div class="info-row">
            <span class="info-label">Atanan Personel</span>
            <span class="info-value">{{ $servis->personel ?? '-' }}</span>
        </div>
    </div>
    
    <!-- Alt Bilgi -->
    <div class="footer">
        Bu belge Negrum ERP tarafından oluşturulmuştur.
    </div>
    
    <script>
        window.onload = function() {
            // Küçük format için otomatik yazdırma
            window.print();
        }
    </script>
</body>
</html>

