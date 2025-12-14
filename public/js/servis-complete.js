$(document).ready(function() {
    // CSRF token
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let currentStep = 1;
    const totalSteps = 4;
    let productCounter = 0;

    // Mobil menü
    $('#menuToggle').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#sidebarOverlay').toggleClass('active');
    });

    $('#sidebarOverlay').on('click', function() {
        $('#sidebar').removeClass('active');
        $('#sidebarOverlay').removeClass('active');
    });

    // Submenu toggle
    $('.menu-item.has-submenu').on('click', function(e) {
        e.preventDefault();
        const submenuId = $(this).data('submenu');
        const $submenu = $('#submenu-' + submenuId);
        
        $('.submenu').not($submenu).removeClass('open');
        $('.menu-item.has-submenu').not(this).removeClass('open');
        
        $(this).toggleClass('open');
        $submenu.toggleClass('open');
    });

    // Step değiştirme
    function showStep(step) {
        $('.step-content').removeClass('active');
        $(`#step${step}`).addClass('active');

        $('.step').removeClass('active completed');
        for (let i = 1; i <= totalSteps; i++) {
            if (i < step) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            } else if (i === step) {
                $(`.step[data-step="${i}"]`).addClass('active');
            }
        }

        if (step === 1) {
            $('#btnPrev').hide();
        } else {
            $('#btnPrev').show();
        }

        if (step === totalSteps) {
            $('#btnNext').hide();
            $('#btnSubmit').show();
        } else {
            $('#btnNext').show();
            $('#btnSubmit').hide();
        }

        // Step 3'e geçerken özet hesapla
        if (step === 3) {
            calculateSummary();
        }

        currentStep = step;
    }

    // İlk step
    showStep(1);

    // Next button
    $('#btnNext').on('click', function() {
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    });

    // Prev button
    $('#btnPrev').on('click', function() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // Step header'a tıklayınca
    $('.step').on('click', function() {
        const step = $(this).data('step');
        if (step <= currentStep || $(this).hasClass('completed')) {
            showStep(step);
        }
    });

    // Ürün ekleme
    $('#btnAddProduct').on('click', function() {
        productCounter++;
        const productHtml = `
            <div class="product-row" data-index="${productCounter}">
                <div class="product-row-header">
                    <span><strong>Ürün/Hizmet ${productCounter}</strong></span>
                    <button type="button" class="btn-remove-product" data-index="${productCounter}">🗑️ Sil</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Stok Adı / Hizmet</label>
                        <input type="text" name="urunler[${productCounter}][stok_adi]" class="form-control" placeholder="Ürün veya hizmet adı" required>
                    </div>
                    <div class="form-group">
                        <label>Stok Kodu</label>
                        <input type="text" name="urunler[${productCounter}][stok_kodu]" class="form-control" placeholder="Opsiyonel">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Para Birimi</label>
                        <select name="urunler[${productCounter}][para_birimi]" class="form-control">
                            <option value="₺ Türk Lirası" selected>₺ Türk Lirası</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Miktar</label>
                        <input type="number" name="urunler[${productCounter}][miktar]" class="form-control product-miktar" data-index="${productCounter}" value="1" step="0.01" min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Birim</label>
                        <select name="urunler[${productCounter}][birim]" class="form-control">
                            <option value="ADET" selected>ADET</option>
                            <option value="KG">KG</option>
                            <option value="LT">LT</option>
                            <option value="M">M</option>
                            <option value="M2">M2</option>
                            <option value="M3">M3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Birim Fiyat</label>
                        <input type="number" name="urunler[${productCounter}][birim_fiyat]" class="form-control product-fiyat" data-index="${productCounter}" value="0" step="0.01" min="0">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>KDV Oranı (%)</label>
                        <input type="number" name="urunler[${productCounter}][kdv_orani]" class="form-control product-kdv" data-index="${productCounter}" value="20" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label>Depo</label>
                        <select name="urunler[${productCounter}][depo]" class="form-control">
                            <option value="Varsayılan" selected>Varsayılan</option>
                        </select>
                    </div>
                </div>
                <div class="form-row" style="background: #e8f5e9; padding: 10px; border-radius: 5px; margin-top: 10px;">
                    <div class="form-group">
                        <label>KDV Tutarı</label>
                        <input type="text" name="urunler[${productCounter}][kdv_tutari]" class="form-control product-kdv-tutar" data-index="${productCounter}" readonly value="0.00">
                    </div>
                    <div class="form-group">
                        <label>Toplam (KDV Hariç)</label>
                        <input type="text" name="urunler[${productCounter}][toplam_kdv_haric]" class="form-control product-toplam-haric" data-index="${productCounter}" readonly value="0.00">
                    </div>
                </div>
                <div class="form-row" style="background: #e3f2fd; padding: 10px; border-radius: 5px;">
                    <div class="form-group">
                        <label><strong>Toplam (KDV Dahil)</strong></label>
                        <input type="text" name="urunler[${productCounter}][toplam_kdv_dahil]" class="form-control product-toplam-dahil" data-index="${productCounter}" readonly value="0.00" style="font-weight: 700; font-size: 16px;">
                    </div>
                </div>
            </div>
        `;
        $('#productList').append(productHtml);
    });

    // Ürün silme
    $(document).on('click', '.btn-remove-product', function() {
        const index = $(this).data('index');
        $(`.product-row[data-index="${index}"]`).remove();
        calculateSummary();
    });

    // Ürün hesaplamaları
    $(document).on('input', '.product-miktar, .product-fiyat, .product-kdv', function() {
        const index = $(this).data('index');
        calculateProduct(index);
    });

    function calculateProduct(index) {
        const miktar = parseFloat($(`.product-miktar[data-index="${index}"]`).val()) || 0;
        const birimFiyat = parseFloat($(`.product-fiyat[data-index="${index}"]`).val()) || 0;
        const kdvOran = parseFloat($(`.product-kdv[data-index="${index}"]`).val()) || 0;

        const toplamHaric = miktar * birimFiyat;
        const kdvTutar = toplamHaric * (kdvOran / 100);
        const toplamDahil = toplamHaric + kdvTutar;

        $(`.product-kdv-tutar[data-index="${index}"]`).val(kdvTutar.toFixed(2));
        $(`.product-toplam-haric[data-index="${index}"]`).val(toplamHaric.toFixed(2));
        $(`.product-toplam-dahil[data-index="${index}"]`).val(toplamDahil.toFixed(2));
    }

    // Genel özet hesaplama
    function calculateSummary() {
        let totalSubtotal = 0;
        let totalKdv = 0;
        let totalWithKdv = 0;

        $('.product-row').each(function() {
            const index = $(this).data('index');
            const subtotal = parseFloat($(`.product-toplam-haric[data-index="${index}"]`).val()) || 0;
            const kdv = parseFloat($(`.product-kdv-tutar[data-index="${index}"]`).val()) || 0;
            const total = parseFloat($(`.product-toplam-dahil[data-index="${index}"]`).val()) || 0;

            totalSubtotal += subtotal;
            totalKdv += kdv;
            totalWithKdv += total;
        });

        $('#summarySubtotal').text(totalSubtotal.toFixed(2) + ' ₺');
        $('#summaryDiscount').text('0.00 ₺');
        $('#summaryKdv').text(totalKdv.toFixed(2) + ' ₺');
        $('#summaryTotal').text(totalWithKdv.toFixed(2) + ' ₺');

        $('#inputSubtotal').val(totalSubtotal.toFixed(2));
        $('#inputDiscount').val('0.00');
        $('#inputKdv').val(totalKdv.toFixed(2));
        $('#inputTotal').val(totalWithKdv.toFixed(2));
    }

    // Periyodik bakım seçilince tarih hesapla
    $('#periyodikBakim').on('change', function() {
        const bakim = $(this).val();
        const today = new Date();
        let targetDate = new Date(today);

        switch(bakim) {
            case 'Aylık':
                targetDate.setMonth(targetDate.getMonth() + 1);
                break;
            case '3 Aylık':
                targetDate.setMonth(targetDate.getMonth() + 3);
                break;
            case '6 Aylık':
                targetDate.setMonth(targetDate.getMonth() + 6);
                break;
            case 'Yıllık':
                targetDate.setFullYear(targetDate.getFullYear() + 1);
                break;
            case '2 Yıllık':
                targetDate.setFullYear(targetDate.getFullYear() + 2);
                break;
        }

        if (bakim) {
            const formattedDate = targetDate.toISOString().split('T')[0];
            $('#bakimTarihi').val(formattedDate);
        } else {
            $('#bakimTarihi').val('');
        }
    });

    // Form submit
    $('#completeForm').on('submit', function(e) {
        e.preventDefault();

        $('.error-text').hide();
        $('#alertBox').hide();

        const $btn = $('#btnSubmit');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner"></span> Tamamlanıyor...');

        const formData = $(this).serialize();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#alertBox')
                        .removeClass('alert-error')
                        .addClass('alert-success')
                        .text(response.message)
                        .fadeIn();
                    
                    $('html, body').animate({ scrollTop: 0 }, 300);
                    
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalText);

                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMsg = 'Lütfen formu kontrol edin!';
                    $('#alertBox')
                        .removeClass('alert-success')
                        .addClass('alert-error')
                        .text(errorMsg)
                        .fadeIn();
                    
                    showStep(1);
                } else {
                    const message = xhr.responseJSON?.message || 'Bir hata oluştu!';
                    $('#alertBox')
                        .removeClass('alert-success')
                        .addClass('alert-error')
                        .text(message)
                        .fadeIn();
                }
                
                $('html, body').animate({ scrollTop: 0 }, 300);
            }
        });
    });

    // İlk ürünü otomatik ekle
    $('#btnAddProduct').trigger('click');
});

