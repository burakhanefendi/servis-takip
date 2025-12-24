@extends('layouts.app')

@section('title', 'Teklif Detayı - ' . $teklif->teklif_no)

@push('styles')
<style>
    .teklif-detail-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .detail-header {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .teklif-title {
        font-size: 24px;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .teklif-no {
        color: #667eea;
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
    }
    
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        font-size: 14px;
        text-decoration: none;
    }
    
    .btn-primary {
        background: #667eea;
        color: white;
    }
    
    .btn-primary:hover {
        background: #5568d3;
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .btn-danger:hover {
        background: #dc2626;
    }
    
    .btn-secondary {
        background: #6b7280;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #4b5563;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .info-label {
        font-size: 12px;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .info-value {
        font-size: 15px;
        color: #2d3748;
        font-weight: 600;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
    
    .status-reddedildi {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .detail-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .customer-info {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .customer-info-item {
        display: flex;
        gap: 10px;
        padding: 12px;
        background: #f8fafc;
        border-radius: 8px;
    }
    
    .customer-info-label {
        font-size: 13px;
        color: #718096;
        min-width: 100px;
        font-weight: 600;
    }
    
    .customer-info-value {
        font-size: 13px;
        color: #2d3748;
        font-weight: 500;
    }
    
    .product-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .product-table th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #4a5568;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    
    .product-table td {
        padding: 12px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
        color: #2d3748;
    }
    
    .product-table tr:hover {
        background: #f9fafb;
    }
    
    .summary-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
        max-width: 400px;
        margin-left: auto;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
        font-size: 14px;
    }
    
    .summary-item:last-child {
        border-bottom: none;
        font-weight: 700;
        font-size: 16px;
        color: #667eea;
        padding-top: 15px;
        margin-top: 5px;
        border-top: 2px solid #e2e8f0;
    }
    
    .summary-label {
        color: #718096;
    }
    
    .summary-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .notes-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 15px;
        color: #2d3748;
        line-height: 1.6;
        white-space: pre-wrap;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
</style>
@endpush

@section('content')
<div class="teklif-detail-container">
    <!-- Header -->
    <div class="detail-header">
        <div class="header-top">
            <h1 class="teklif-title">
                <i class="fas fa-file-invoice"></i>
                Teklif No: <span class="teklif-no">{{ $teklif->teklif_no }}</span>
            </h1>
            
            <div class="action-buttons">
                <a href="{{ route('teklif.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Geri
                </a>
                <a href="{{ route('teklif.edit', $teklif->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Düzenle
                </a>
                <a href="{{ route('teklif.pdf', $teklif->id) }}" target="_blank" class="btn btn-success">
                    <i class="fas fa-file-pdf"></i>
                    PDF Görüntüle
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $teklif->id }})">
                    <i class="fas fa-trash"></i>
                    Sil
                </button>
            </div>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Teklif Başlığı</span>
                <span class="info-value">{{ $teklif->baslik ?? '-' }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Başlangıç Tarihi</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($teklif->baslangic_tarihi)->format('d.m.Y') }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Geçerlilik Tarihi</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($teklif->gecerlilik_tarihi)->format('d.m.Y') }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Durum</span>
                <span class="status-badge status-{{ $teklif->durum }}">
                    @if($teklif->durum == 'taslak')
                        <i class="fas fa-edit"></i> Taslak
                    @elseif($teklif->durum == 'gonderildi')
                        <i class="fas fa-paper-plane"></i> Gönderildi
                    @elseif($teklif->durum == 'onaylandi')
                        <i class="fas fa-check-circle"></i> Onaylandı
                    @elseif($teklif->durum == 'reddedildi')
                        <i class="fas fa-times-circle"></i> Reddedildi
                    @endif
                </span>
            </div>
        </div>
    </div>
    
    <!-- Müşteri Bilgileri -->
    <div class="detail-section">
        <h2 class="section-title">
            <i class="fas fa-user"></i>
            Müşteri Bilgileri
        </h2>
        
        <div class="customer-info">
            <div class="customer-info-item">
                <span class="customer-info-label">Cari Adı:</span>
                <span class="customer-info-value">{{ $teklif->cariHesap->cari_hesap_adi }}</span>
            </div>
            
            <div class="customer-info-item">
                <span class="customer-info-label">Müşteri Kodu:</span>
                <span class="customer-info-value">{{ $teklif->cariHesap->musteri_kodu ?? '-' }}</span>
            </div>
            
            <div class="customer-info-item">
                <span class="customer-info-label">GSM:</span>
                <span class="customer-info-value">{{ $teklif->cariHesap->gsm ?? '-' }}</span>
            </div>
            
            <div class="customer-info-item">
                <span class="customer-info-label">Telefon:</span>
                <span class="customer-info-value">{{ $teklif->cariHesap->sabit_telefon ?? '-' }}</span>
            </div>
            
            <div class="customer-info-item">
                <span class="customer-info-label">E-posta:</span>
                <span class="customer-info-value">{{ $teklif->cariHesap->eposta ?? '-' }}</span>
            </div>
            
            <div class="customer-info-item">
                <span class="customer-info-label">Adres:</span>
                <span class="customer-info-value">{{ $teklif->cariHesap->adres ?? '-' }} {{ $teklif->cariHesap->ilce ?? '' }}/{{ $teklif->cariHesap->il ?? '' }}</span>
            </div>
        </div>
    </div>
    
    <!-- Ürün/Hizmet Listesi -->
    <div class="detail-section">
        <h2 class="section-title">
            <i class="fas fa-box"></i>
            Teklif Edilen Ürün ve Hizmetler
        </h2>
        
        @if($teklif->urunler->count() > 0)
            <table class="product-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>SKU</th>
                        <th>STOK TANIMI</th>
                        <th>MİKTAR</th>
                        <th>BİRİM FİYAT</th>
                        <th>İNDİRİM</th>
                        <th>KDV (%)</th>
                        <th>KDV TUTARI</th>
                        <th>TUTAR (KDV HARİÇ)</th>
                        <th>TUTAR (KDV DAHİL)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teklif->urunler->sortBy('sira_no') as $index => $urun)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $urun->sku ?? '-' }}</td>
                        <td><strong>{{ $urun->stok_tanimi }}</strong></td>
                        <td>{{ number_format($urun->miktar, 2) }} {{ $urun->birim }}</td>
                        <td>{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->birim_fiyat, 2) }}</td>
                        <td>
                            @if($urun->indirim_tutar > 0)
                                {{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->indirim_tutar, 2) }}
                                ({{ number_format($urun->indirim_oran, 0) }}%)
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ number_format($urun->kdv_oran, 0) }}%</td>
                        <td>{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->kdv_tutar, 2) }}</td>
                        <td>{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->tutar_kdv_haric, 2) }}</td>
                        <td><strong>{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($urun->tutar_kdv_dahil, 2) }}</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Özet -->
            <div class="summary-box">
                <div class="summary-item">
                    <span class="summary-label">Mal/Hizmet Tutarı:</span>
                    <span class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->mal_hizmet_tutari, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Toplam İskonto:</span>
                    <span class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->toplam_iskonto, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Ara Toplam (KDV Matrahı):</span>
                    <span class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->ara_toplam, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Hesaplanan KDV:</span>
                    <span class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->hesaplanan_kdv, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Genel Toplam:</span>
                    <span class="summary-value">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->genel_toplam, 2) }}</span>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5;"></i>
                <div>Bu teklife henüz ürün eklenmemiş</div>
            </div>
        @endif
    </div>
    
    <!-- Notlar -->
    @if($teklif->notlar)
    <div class="detail-section">
        <h2 class="section-title">
            <i class="fas fa-sticky-note"></i>
            Teklif İçeriği ve Koşulları
        </h2>
        
        <div class="notes-box">
            {{ $teklif->notlar }}
        </div>
    </div>
    @endif
</div>

<script>
function confirmDelete(teklifId) {
    if (confirm('Bu teklifi silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: '/teklif/' + teklifId,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route("teklif.index") }}';
                }
            },
            error: function(xhr) {
                alert('Hata: ' + (xhr.responseJSON?.message || 'Bir hata oluştu.'));
            }
        });
    }
}
</script>
@endsection

