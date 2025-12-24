@extends('layouts.app')

@section('title', 'Yeni Teklif Oluştur')

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
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        display: none;
    }
    
    .autocomplete-item {
        padding: 10px 15px;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
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
            <i class="fas fa-file-invoice"></i>
            Yeni Teklif Oluştur
        </h1>
    </div>
    
    <form id="teklifForm">
        @csrf
        
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
                        <input type="text" id="musteriSearch" placeholder="Müşteri adı yazın..." autocomplete="off">
                        <input type="hidden" id="cari_hesap_id" name="cari_hesap_id" required>
                        <div id="autocompleteResults" class="autocomplete-results"></div>
                    </div>
                </div>
                
                <div id="customerInfo" class="customer-info">
                    <div style="text-align: center; color: #9ca3af; padding: 30px 10px;">
                        <i class="fas fa-user-circle" style="font-size: 32px; margin-bottom: 10px;"></i>
                        <div>Müşteri seçiniz</div>
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
                    <input type="text" name="teklif_no" value="{{ $teklifNo }}" readonly style="background: #f8fafc;">
                </div>
                
                <div class="form-group">
                    <label>Teklif Başlığı</label>
                    <input type="text" name="teklif_baslik" placeholder="Teklif başlığını girin">
                </div>
                
                <div class="form-group">
                    <label>Başlangıç Tarihi</label>
                    <input type="date" name="baslangic_tarihi" value="{{ date('Y-m-d') }}" required>
                </div>
                
                <div class="form-group">
                    <label>Geçerlilik Tarihi</label>
                    <input type="date" name="gecerlilik_tarihi" value="{{ date('Y-m-d', strtotime('+7 days')) }}" required>
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
                        <button type="button" class="currency-btn active" data-currency="TRY">₺ TRY</button>
                        <button type="button" class="currency-btn" data-currency="USD">$ USD</button>
                        <button type="button" class="currency-btn" data-currency="EUR">€ EUR</button>
                        <button type="button" class="currency-btn" data-currency="GBP">£ GBP</button>
                    </div>
                    <input type="hidden" name="para_birimi" value="TRY">
                </div>
                
                <div class="form-group">
                    <label>Teklif Fotoğrafları</label>
                    <label class="toggle-switch">
                        <input type="checkbox" name="fotograflar_goster">
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
                <table class="product-table" id="productTable" style="display: none;">
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
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Ürünler buraya eklenecek -->
                    </tbody>
                </table>
                
                <div id="emptyState" class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <div>Henüz ürün eklenmedi</div>
                    <div style="font-size: 13px; margin-top: 5px;">Yukarıdaki "Ekle" butonuna tıklayarak ürün ekleyebilirsiniz</div>
                </div>
            </div>
            
            <!-- Özet Bilgileri -->
            <div class="summary-box" style="max-width: 400px; margin-left: auto;">
                <div class="summary-item">
                    <span class="summary-label">Mal Hizmet Tutarı:</span>
                    <span class="summary-value" id="summaryMalHizmet">₺0,00</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Toplam İskonto:</span>
                    <span class="summary-value" id="summaryIskonto">₺0,00</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Ara Toplam (KDV Matrahı):</span>
                    <span class="summary-value" id="summaryAraToplam">₺0,00</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Hesaplanan KDV:</span>
                    <span class="summary-value" id="summaryKDV">₺0,00</span>
                </div>
            </div>
        </div>
        
        <!-- Kaydet Butonu -->
        <div style="display: flex; justify-content: flex-end; gap: 15px;">
            <a href="{{ route('teklif.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Vazgeç
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i>
                Teklifi Kaydet
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let products = [];
    let editingIndex = -1;
    
    // Para birimi seçimi
    $('.currency-btn').on('click', function() {
        $('.currency-btn').removeClass('active');
        $(this).addClass('active');
        $('input[name="para_birimi"]').val($(this).data('currency'));
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
                    <td>${currencySymbol}${product.birim_fiyat.toFixed(2)}</td>
                    <td>${currencySymbol}${product.indirim_tutar.toFixed(2)}</td>
                    <td>%${product.indirim_oran}</td>
                    <td>%${product.kdv_oran}</td>
                    <td>${currencySymbol}${product.kdv_tutar.toFixed(2)}</td>
                    <td>%${product.tevkifat}</td>
                    <td>${currencySymbol}${product.tutar_kdv_haric.toFixed(2)}</td>
                    <td><strong>${currencySymbol}${product.tutar_kdv_dahil.toFixed(2)}</strong></td>
                </tr>
            `;
        });
        
        $('#productTableBody').html(html);
        updateSummary();
    }
    
    function updateSummary() {
        let currencySymbol = $('.currency-btn.active').text().split(' ')[0];
        
        const malHizmet = products.reduce((sum, p) => sum + (p.miktar * p.birim_fiyat), 0);
        const iskonto = products.reduce((sum, p) => sum + p.indirim_tutar, 0);
        const araToplam = products.reduce((sum, p) => sum + p.tutar_kdv_haric, 0);
        const kdv = products.reduce((sum, p) => sum + p.kdv_tutar, 0);
        
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
            url: '{{ route("teklif.store") }}',
            method: 'POST',
            data: $.param(formData),
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
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

