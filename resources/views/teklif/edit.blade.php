@extends('layouts.app')

@section('title', 'Teklif Düzenle - ' . $teklif->teklif_no)

@push('styles')
<style>
    .teklif-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
        padding-bottom: 12px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .card-header i {
        font-size: 18px;
        color: #667eea;
    }
    
    .card-title {
        font-size: 16px;
        font-weight: 600;
        color: #2d3748;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
    }
    
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .customer-info {
        background: #f8fafc;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        padding: 15px;
        min-height: 120px;
        margin-top: 10px;
    }
    
    .customer-info-item {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 13px;
    }
    
    .customer-info-label {
        color: #718096;
        min-width: 80px;
    }
    
    .customer-info-value {
        color: #2d3748;
        font-weight: 500;
    }
    
    .currency-buttons {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 15px;
    }
    
    .currency-btn {
        padding: 10px;
        border: 2px solid #e2e8f0;
        background: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.2s;
        text-align: center;
    }
    
    .currency-btn.active {
        border-color: #667eea;
        background: #667eea;
        color: white;
    }
    
    .toggle-switch {
        position: relative;
        width: 50px;
        height: 26px;
        margin-bottom: 10px;
    }
    
    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e0;
        transition: .4s;
        border-radius: 34px;
    }
    
    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .toggle-slider {
        background-color: #667eea;
    }
    
    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }
    
    .toggle-label {
        font-size: 12px;
        color: #718096;
        margin-top: 5px;
    }
    
    .product-section {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
    }
    
    .btn-add {
        background: #667eea;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    
    .btn-add:hover {
        background: #5568d3;
        transform: translateY(-1px);
    }
    
    .product-form {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .product-form-full {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 15px;
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
    }
    
    .btn-success {
        background: #10b981;
        color: white;
    }
    
    .btn-success:hover {
        background: #059669;
    }
    
    .btn-warning {
        background: #f59e0b;
        color: white;
    }
    
    .btn-warning:hover {
        background: #d97706;
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
    
    .product-table .actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-icon {
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
    }
    
    .btn-icon:hover {
        transform: translateY(-1px);
    }
    
    .summary-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
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
    }
    
    .summary-label {
        color: #718096;
    }
    
    .summary-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    .autocomplete-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
    }
    
    .autocomplete-item {
        padding: 8px 12px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
        line-height: 1.4;
    }
    
    .autocomplete-item strong {
        font-size: 14px;
        font-weight: 600;
        color: #2d3748;
        display: block;
        margin-bottom: 2px;
    }
    
    .autocomplete-item small {
        font-size: 12px;
        color: #718096;
    }
    
    .autocomplete-item:hover {
        background: #f8fafc;
    }
    
    .autocomplete-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="teklif-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Teklif Düzenle - {{ $teklif->teklif_no }}
        </h1>
    </div>
    
    <form id="teklifForm">
        @csrf
        @method('PUT')
        
        <!-- Üst Kartlar -->
        <div class="form-row">
            <!-- Cari Hesap -->
            <div class="form-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <span class="card-title">Cari Hesap</span>
                </div>
                
                <div class="form-group">
                    <label>Müşteri Seçin</label>
                    <div style="position: relative;">
                        <input type="text" id="musteriSearch" placeholder="Müşteri adı yazın..." autocomplete="off" value="{{ $teklif->cariHesap->cari_hesap_adi }}">
                        <input type="hidden" id="cari_hesap_id" name="cari_hesap_id" value="{{ $teklif->cari_hesap_id }}" required>
                        <div id="autocompleteResults" class="autocomplete-results"></div>
                    </div>
                </div>
                
                <div id="customerInfo" class="customer-info">
                    <div class="customer-info-item">
                        <span class="customer-info-label">Cari Adı:</span>
                        <span class="customer-info-value">{{ $teklif->cariHesap->cari_hesap_adi }}</span>
                    </div>
                    <div class="customer-info-item">
                        <span class="customer-info-label">Telefon:</span>
                        <span class="customer-info-value">{{ $teklif->cariHesap->gsm ?? $teklif->cariHesap->sabit_telefon ?? '-' }}</span>
                    </div>
                    <div class="customer-info-item">
                        <span class="customer-info-label">E-posta:</span>
                        <span class="customer-info-value">{{ $teklif->cariHesap->eposta ?? '-' }}</span>
                    </div>
                    <div class="customer-info-item">
                        <span class="customer-info-label">Adres:</span>
                        <span class="customer-info-value">{{ $teklif->cariHesap->adres ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Teklif Bilgileri -->
            <div class="form-card">
                <div class="card-header">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="card-title">Teklif Bilgileri</span>
                </div>
                
                <div class="form-group">
                    <label>Teklif No</label>
                    <input type="text" name="teklif_no" value="{{ $teklif->teklif_no }}" readonly style="background: #f8fafc;">
                </div>
                
                <div class="form-group">
                    <label>Teklif Başlığı</label>
                    <input type="text" name="teklif_baslik" placeholder="Teklif başlığını girin" value="{{ $teklif->baslik }}">
                </div>
                
                <div class="form-group">
                    <label>Başlangıç Tarihi</label>
                    <input type="date" name="baslangic_tarihi" value="{{ \Carbon\Carbon::parse($teklif->baslangic_tarihi)->format('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label>Geçerlilik Tarihi</label>
                    <input type="date" name="gecerlilik_tarihi" value="{{ \Carbon\Carbon::parse($teklif->gecerlilik_tarihi)->format('Y-m-d') }}" required>
                </div>
            </div>
            
            <!-- Ayarlar -->
            <div class="form-card">
                <div class="card-header">
                    <i class="fas fa-cog"></i>
                    <span class="card-title">Ayarlar</span>
                </div>
                
                <div class="form-group">
                    <label>Para Birimi</label>
                    <div class="currency-buttons">
                        <button type="button" class="currency-btn {{ $teklif->para_birimi == 'TRY' ? 'active' : '' }}" data-currency="TRY">₺ TRY</button>
                        <button type="button" class="currency-btn {{ $teklif->para_birimi == 'USD' ? 'active' : '' }}" data-currency="USD">$ USD</button>
                        <button type="button" class="currency-btn {{ $teklif->para_birimi == 'EUR' ? 'active' : '' }}" data-currency="EUR">€ EUR</button>
                        <button type="button" class="currency-btn {{ $teklif->para_birimi == 'GBP' ? 'active' : '' }}" data-currency="GBP">£ GBP</button>
                    </div>
                    <input type="hidden" name="para_birimi" value="{{ $teklif->para_birimi }}">
                </div>
                
                <div class="form-group">
                    <label>Teklif Fotoğrafları</label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="fotograflar_goster" {{ $teklif->fotograflar_goster ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                    <div class="toggle-label">Sadece müşterilerde gösterilecek</div>
                </div>
            </div>
        </div>
        
        <!-- Ürün/Hizmet Ekleme Bölümü -->
        <div class="product-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-box"></i>
                    Teklif Verilecek Hizmet ve Ürünler
                </h2>
                <button type="button" class="btn-add" id="btnShowProductForm">
                    <i class="fas fa-plus"></i>
                    Ekle
                </button>
            </div>
            
            <!-- Ürün Ekleme Formu (başlangıçta gizli) -->
            <div id="productFormContainer" style="display: none;">
                <div class="product-form">
                    <div class="form-group">
                        <label>Ürün / Hizmet</label>
                        <input type="text" id="urun_adi" placeholder="Stok adı veya kodunu girin">
                    </div>
                    
                    <div class="form-group">
                        <label>Miktar</label>
                        <div style="display: flex; gap: 5px;">
                            <input type="number" id="miktar" value="1" step="0.01" style="width: 70px;">
                            <select id="birim" style="flex: 1;">
                                <option value="ADET">ADET</option>
                                <option value="KG">KG</option>
                                <option value="LT">LT</option>
                                <option value="M">M</option>
                                <option value="M2">M²</option>
                                <option value="M3">M³</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Birim Fiyat</label>
                        <input type="number" id="birim_fiyat" value="0" step="0.01">
                    </div>
                    
                    <div class="form-group">
                        <label>KDV Oranı</label>
                        <div style="display: flex; gap: 5px; align-items: center;">
                            <input type="number" id="kdv_oran" value="20" step="0.01" style="flex: 1;">
                            <span style="font-weight: 600;">%</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>KDV Tutarı</label>
                        <input type="number" id="kdv_tutar" value="0" step="0.01" readonly style="background: #f8fafc;">
                    </div>
                </div>
                
                <div class="product-form-full">
                    <div class="form-group">
                        <label>Depo</label>
                        <select id="depo">
                            <option value="Varsayılan">Varsayılan</option>
                            <option value="Ana Depo">Ana Depo</option>
                            <option value="Şube Depo">Şube Depo</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Toplam (KDV Hariç)</label>
                        <input type="number" id="toplam_kdv_haric" value="0" step="0.01" readonly style="background: #f8fafc;">
                    </div>
                    
                    <div class="form-group">
                        <label>Toplam (KDV Dahil)</label>
                        <input type="number" id="toplam_kdv_dahil" value="0" step="0.01" readonly style="background: #f8fafc;">
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-success" id="btnAddProduct">
                        <i class="fas fa-check"></i>
                        Ürünü Ekle
                    </button>
                    <button type="button" class="btn btn-warning" id="btnUpdateProduct" style="display: none;">
                        <i class="fas fa-edit"></i>
                        Güncelle
                    </button>
                    <button type="button" class="btn btn-danger" id="btnDeleteProduct" style="display: none;">
                        <i class="fas fa-trash"></i>
                        Sil
                    </button>
                    <button type="button" class="btn btn-secondary" id="btnCancelProduct">
                        <i class="fas fa-times"></i>
                        Vazgeç
                    </button>
                </div>
            </div>
            
            <!-- Ürün Listesi -->
            <div id="productListContainer">
                <table class="product-table" id="productTable" style="{{ $teklif->urunler->count() > 0 ? '' : 'display: none;' }}">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>STOK TANIMI</th>
                            <th>MİKTAR</th>
                            <th>BİRİM FİYAT</th>
                            <th>İNDİRİM</th>
                            <th>ORAN</th>
                            <th>KDV</th>
                            <th>KDV TUTARI</th>
                            <th>TEVKİFAT</th>
                            <th>TUTAR (KDV HARİÇ)</th>
                            <th>TUTAR (KDV DAHİL)</th>
                            <th>İŞLEM</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Ürünler JS ile yüklenecek -->
                    </tbody>
                </table>
                
                <div id="emptyState" class="empty-state" style="{{ $teklif->urunler->count() > 0 ? 'display: none;' : '' }}">
                    <i class="fas fa-inbox"></i>
                    <div>Henüz ürün eklenmedi</div>
                    <div style="font-size: 13px; margin-top: 5px;">Yukarıdaki "Ekle" butonuna tıklayarak ürün ekleyebilirsiniz</div>
                </div>
            </div>
            
            <!-- Özet Bilgileri -->
            <div class="summary-box" style="max-width: 400px; margin-left: auto;">
                <div class="summary-item">
                    <span class="summary-label">Mal Hizmet Tutarı:</span>
                    <span class="summary-value" id="summaryMalHizmet">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->mal_hizmet_tutari ?? 0, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Toplam İskonto:</span>
                    <span class="summary-value" id="summaryIskonto">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->toplam_iskonto ?? 0, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Ara Toplam (KDV Matrahı):</span>
                    <span class="summary-value" id="summaryAraToplam">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->ara_toplam ?? 0, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Hesaplanan KDV:</span>
                    <span class="summary-value" id="summaryKDV">{{ $teklif->para_birimi == 'TRY' ? '₺' : $teklif->para_birimi }}{{ number_format($teklif->hesaplanan_kdv ?? 0, 2) }}</span>
                </div>
            </div>
        </div>
        
        <!-- Teklif İçeriği ve Koşulları -->
        <div class="form-card" style="margin-top: 20px;">
            <div class="card-header">
                <i class="fas fa-file-alt"></i>
                <span class="card-title">Teklif İçeriği ve Koşulları</span>
            </div>
            
            <div class="form-group">
                <textarea name="notlar" rows="6" placeholder="Teklif içeriği, ödeme koşulları, teslimat süresi ve diğer önemli notları buraya yazabilirsiniz...">{{ $teklif->notlar }}</textarea>
            </div>
        </div>
        
        <!-- Kaydet Butonu -->
        <div style="display: flex; justify-content: flex-end; gap: 15px; margin-top: 20px;">
            <a href="{{ route('teklif.show', $teklif->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Vazgeç
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i>
                Değişiklikleri Kaydet
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let products = {!! json_encode($teklif->urunler->map(function($urun) {
        return [
            'id' => $urun->id,
            'sku' => $urun->sku,
            'stok_tanimi' => $urun->stok_tanimi,
            'miktar' => floatval($urun->miktar),
            'birim' => $urun->birim,
            'birim_fiyat' => floatval($urun->birim_fiyat),
            'indirim_oran' => floatval($urun->indirim_oran),
            'indirim_tutar' => floatval($urun->indirim_tutar),
            'kdv_oran' => floatval($urun->kdv_oran),
            'kdv_tutar' => floatval($urun->kdv_tutar),
            'tevkifat' => floatval($urun->tevkifat ?? 0),
            'tutar_kdv_haric' => floatval($urun->tutar_kdv_haric),
            'tutar_kdv_dahil' => floatval($urun->tutar_kdv_dahil),
            'depo' => $urun->depo
        ];
    })->values()) !!};
    
    let editingIndex = -1;
    const teklifId = {{ $teklif->id }};
    
    // Sayfa yüklendiğinde ürünleri göster
    renderProductTable();
    
    // Para birimi seçimi
    $('.currency-btn').on('click', function() {
        $('.currency-btn').removeClass('active');
        $(this).addClass('active');
        $('input[name="para_birimi"]').val($(this).data('currency'));
        renderProductTable();
    });
    
    // Müşteri arama (autocomplete)
    let searchTimeout;
    $('#musteriSearch').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        
        if (query.length < 2) {
            $('#autocompleteResults').hide();
            return;
        }
        
        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '{{ route("api.cari.search") }}',
                data: { q: query },
                success: function(data) {
                    let html = '';
                    data.forEach(function(cari) {
                        html += `<div class="autocomplete-item" data-id="${cari.id}" data-json='${JSON.stringify(cari)}'>
                            <strong>${cari.cari_hesap_adi}</strong>
                            ${cari.gsm ? '<br><small>' + cari.gsm + '</small>' : ''}
                        </div>`;
                    });
                    $('#autocompleteResults').html(html).show();
                }
            });
        }, 300);
    });
    
    // Müşteri seçimi
    $(document).on('click', '.autocomplete-item', function() {
        const cari = JSON.parse($(this).attr('data-json'));
        $('#cari_hesap_id').val(cari.id);
        $('#musteriSearch').val(cari.cari_hesap_adi);
        $('#autocompleteResults').hide();
        
        // Müşteri bilgilerini göster
        let html = `
            <div class="customer-info-item">
                <span class="customer-info-label">Cari Adı:</span>
                <span class="customer-info-value">${cari.cari_hesap_adi}</span>
            </div>
            <div class="customer-info-item">
                <span class="customer-info-label">Telefon:</span>
                <span class="customer-info-value">${cari.gsm || cari.sabit_telefon || '-'}</span>
            </div>
            <div class="customer-info-item">
                <span class="customer-info-label">E-posta:</span>
                <span class="customer-info-value">${cari.eposta || '-'}</span>
            </div>
            <div class="customer-info-item">
                <span class="customer-info-label">Adres:</span>
                <span class="customer-info-value">${cari.adres || '-'}</span>
            </div>
        `;
        $('#customerInfo').html(html);
    });
    
    // Dışarı tıklanınca autocomplete'i kapat
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#musteriSearch, #autocompleteResults').length) {
            $('#autocompleteResults').hide();
        }
    });
    
    // Ürün formu göster/gizle
    $('#btnShowProductForm').on('click', function() {
        $('#productFormContainer').slideDown();
        editingIndex = -1;
        clearProductForm();
    });
    
    $('#btnCancelProduct').on('click', function() {
        $('#productFormContainer').slideUp();
        editingIndex = -1;
        clearProductForm();
    });
    
    // Hesaplamalar
    function calculateProduct() {
        const miktar = parseFloat($('#miktar').val()) || 0;
        const birimFiyat = parseFloat($('#birim_fiyat').val()) || 0;
        const kdvOran = parseFloat($('#kdv_oran').val()) || 0;
        
        const toplamKdvHaric = miktar * birimFiyat;
        const kdvTutar = toplamKdvHaric * (kdvOran / 100);
        const toplamKdvDahil = toplamKdvHaric + kdvTutar;
        
        $('#kdv_tutar').val(kdvTutar.toFixed(2));
        $('#toplam_kdv_haric').val(toplamKdvHaric.toFixed(2));
        $('#toplam_kdv_dahil').val(toplamKdvDahil.toFixed(2));
    }
    
    $('#miktar, #birim_fiyat, #kdv_oran').on('input', calculateProduct);
    
    // Ürün ekle
    $('#btnAddProduct').on('click', function() {
        const product = {
            sku: '',
            stok_tanimi: $('#urun_adi').val(),
            miktar: parseFloat($('#miktar').val()),
            birim: $('#birim').val(),
            birim_fiyat: parseFloat($('#birim_fiyat').val()),
            indirim_oran: 0,
            indirim_tutar: 0,
            kdv_oran: parseFloat($('#kdv_oran').val()),
            kdv_tutar: parseFloat($('#kdv_tutar').val()),
            tevkifat: 0,
            tutar_kdv_haric: parseFloat($('#toplam_kdv_haric').val()),
            tutar_kdv_dahil: parseFloat($('#toplam_kdv_dahil').val()),
            depo: $('#depo').val()
        };
        
        if (!product.stok_tanimi) {
            alert('Lütfen ürün/hizmet adı giriniz.');
            return;
        }
        
        products.push(product);
        renderProductTable();
        $('#productFormContainer').slideUp();
        clearProductForm();
    });
    
    function clearProductForm() {
        $('#urun_adi').val('');
        $('#miktar').val(1);
        $('#birim').val('ADET');
        $('#birim_fiyat').val(0);
        $('#kdv_oran').val(20);
        $('#depo').val('Varsayılan');
        calculateProduct();
    }
    
    function renderProductTable() {
        if (products.length === 0) {
            $('#productTable').hide();
            $('#emptyState').show();
            return;
        }
        
        $('#emptyState').hide();
        $('#productTable').show();
        
        let html = '';
        let currencySymbol = $('.currency-btn.active').text().charAt(0);
        
        products.forEach(function(product, index) {
            html += `
                <tr>
                    <td>${product.sku || '-'}</td>
                    <td>${product.stok_tanimi}</td>
                    <td>${product.miktar} ${product.birim}</td>
                    <td>${currencySymbol}${parseFloat(product.birim_fiyat).toFixed(2)}</td>
                    <td>${currencySymbol}${parseFloat(product.indirim_tutar).toFixed(2)}</td>
                    <td>%${parseFloat(product.indirim_oran)}</td>
                    <td>%${parseFloat(product.kdv_oran)}</td>
                    <td>${currencySymbol}${parseFloat(product.kdv_tutar).toFixed(2)}</td>
                    <td>%${parseFloat(product.tevkifat)}</td>
                    <td>${currencySymbol}${parseFloat(product.tutar_kdv_haric).toFixed(2)}</td>
                    <td><strong>${currencySymbol}${parseFloat(product.tutar_kdv_dahil).toFixed(2)}</strong></td>
                    <td>
                        <button type="button" class="btn-icon btn-danger" onclick="removeProduct(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#productTableBody').html(html);
        updateSummary();
    }
    
    window.removeProduct = function(index) {
        if (confirm('Bu ürünü silmek istediğinizden emin misiniz?')) {
            products.splice(index, 1);
            renderProductTable();
        }
    };
    
    function updateSummary() {
        let currencySymbol = $('.currency-btn.active').text().split(' ')[0];
        
        const malHizmet = products.reduce((sum, p) => sum + (parseFloat(p.miktar) * parseFloat(p.birim_fiyat)), 0);
        const iskonto = products.reduce((sum, p) => sum + parseFloat(p.indirim_tutar), 0);
        const araToplam = products.reduce((sum, p) => sum + parseFloat(p.tutar_kdv_haric), 0);
        const kdv = products.reduce((sum, p) => sum + parseFloat(p.kdv_tutar), 0);
        
        $('#summaryMalHizmet').text(currencySymbol + malHizmet.toFixed(2));
        $('#summaryIskonto').text(currencySymbol + iskonto.toFixed(2));
        $('#summaryAraToplam').text(currencySymbol + araToplam.toFixed(2));
        $('#summaryKDV').text(currencySymbol + kdv.toFixed(2));
    }
    
    // Form gönderimi
    $('#teklifForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#cari_hesap_id').val()) {
            alert('Lütfen bir müşteri seçiniz.');
            return;
        }
        
        const formData = $(this).serializeArray();
        formData.push({ name: 'products', value: JSON.stringify(products) });
        
        $.ajax({
            url: '{{ route("teklif.update", $teklif->id) }}',
            method: 'POST',
            data: $.param(formData),
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = '{{ route("teklif.show", $teklif->id) }}';
                }
            },
            error: function(xhr) {
                alert('Hata: ' + (xhr.responseJSON?.message || 'Bir hata oluştu.'));
            }
        });
    });
});
</script>
@endpush

