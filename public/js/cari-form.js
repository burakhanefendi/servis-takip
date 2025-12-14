$(document).ready(function() {
    // CSRF token ayarla
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // İl ve İlçe Dropdown'larını Doldur
    function populateCities() {
        const $ilSelect = $('#il');
        
        // İlleri ekle
        turkeyCities.forEach(function(city) {
            $ilSelect.append(`<option value="${city.text}">${city.text}</option>`);
        });

        // Eğer edit sayfasındaysak, seçili değerleri ayarla
        const selectedIl = $ilSelect.data('selected');
        const selectedIlce = $('#ilce').data('selected');
        
        if (selectedIl) {
            $ilSelect.val(selectedIl);
            // İlçeleri doldur
            populateDistricts(selectedIl, selectedIlce);
        }
    }

    // İlçeleri doldur
    function populateDistricts(cityName, selectedDistrict = null) {
        const $ilceSelect = $('#ilce');
        $ilceSelect.empty().append('<option value="">İlçe Seçiniz...</option>');
        
        if (!cityName) {
            return;
        }

        // Seçilen ili bul
        const city = turkeyCities.find(c => c.text === cityName);
        
        if (city && city.districts) {
            // İlçeleri ekle
            city.districts.forEach(function(district) {
                $ilceSelect.append(`<option value="${district.text}">${district.text}</option>`);
            });

            // Eğer seçili ilçe varsa ayarla
            if (selectedDistrict) {
                $ilceSelect.val(selectedDistrict);
            }
        }
    }

    // İl değiştiğinde ilçeleri güncelle
    $('#il').on('change', function() {
        const cityName = $(this).val();
        populateDistricts(cityName);
    });

    // Sayfa yüklendiğinde illeri doldur
    populateCities();

    // Mobil menü toggle
    $('#menuToggle').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#sidebarOverlay').toggleClass('active');
    });

    // Overlay'e tıklayınca menüyü kapat
    $('#sidebarOverlay').on('click', function() {
        $('#sidebar').removeClass('active');
        $('#sidebarOverlay').removeClass('active');
    });

    // Submenu toggle
    $('.menu-item.has-submenu').on('click', function(e) {
        e.preventDefault();
        const submenuId = $(this).data('submenu');
        const $submenu = $('#submenu-' + submenuId);
        
        // Diğer submenüleri kapat
        $('.submenu').not($submenu).removeClass('open');
        $('.menu-item.has-submenu').not(this).removeClass('open');
        
        // Bu submenuyu aç/kapat
        $(this).toggleClass('open');
        $submenu.toggleClass('open');
    });

    // Yetkili kişi sayacı
    let yetkiliCounter = 0;

    // Yetkili kişi ekle
    $('#btnAddYetkili').on('click', function() {
        yetkiliCounter++;
        const yetkiliHtml = `
            <div class="yetkili-item" data-index="${yetkiliCounter}">
                <div class="yetkili-item-header">
                    <span class="yetkili-item-title">Yetkili Kişi ${yetkiliCounter}</span>
                    <button type="button" class="btn-remove-yetkili" data-index="${yetkiliCounter}">🗑️ Sil</button>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Ad Soyad</label>
                        <input type="text" name="yetkili_kisiler[${yetkiliCounter}][ad_soyad]" class="form-control" placeholder="Ad Soyad">
                    </div>
                    <div class="form-group">
                        <label>Ünvan</label>
                        <input type="text" name="yetkili_kisiler[${yetkiliCounter}][unvan]" class="form-control" placeholder="Ünvan">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Telefon</label>
                        <input type="tel" name="yetkili_kisiler[${yetkiliCounter}][telefon]" class="form-control" placeholder="0555 555 55 55">
                    </div>
                    <div class="form-group">
                        <label>E-posta</label>
                        <input type="email" name="yetkili_kisiler[${yetkiliCounter}][eposta]" class="form-control" placeholder="ornek@email.com">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Dahili</label>
                        <input type="text" name="yetkili_kisiler[${yetkiliCounter}][dahili]" class="form-control" placeholder="101">
                    </div>
                </div>
            </div>
        `;
        $('#yetkiliList').append(yetkiliHtml);
    });

    // Yetkili kişi sil
    $(document).on('click', '.btn-remove-yetkili', function() {
        const index = $(this).data('index');
        $(`.yetkili-item[data-index="${index}"]`).remove();
    });

    // Form submit
    $('#cariForm').on('submit', function(e) {
        e.preventDefault();

        // Hata mesajlarını temizle
        $('.error-text').hide();
        $('#alertBox').hide();

        // Button'u disable et
        const $btn = $('#btnSubmit');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner"></span> Kaydediliyor...');

        // Form verilerini topla
        const formData = new FormData(this);
        
        // Yetkili kişileri object olarak topla
        const yetkiliKisiler = [];
        $('.yetkili-item').each(function() {
            const index = $(this).data('index');
            const adSoyad = $(`input[name="yetkili_kisiler[${index}][ad_soyad]"]`).val();
            const unvan = $(`input[name="yetkili_kisiler[${index}][unvan]"]`).val();
            const telefon = $(`input[name="yetkili_kisiler[${index}][telefon]"]`).val();
            const eposta = $(`input[name="yetkili_kisiler[${index}][eposta]"]`).val();
            const dahili = $(`input[name="yetkili_kisiler[${index}][dahili]"]`).val();
            
            if (adSoyad) {
                yetkiliKisiler.push({
                    ad_soyad: adSoyad,
                    unvan: unvan,
                    telefon: telefon,
                    eposta: eposta,
                    dahili: dahili
                });
            }
        });

        // FormData'yı object'e çevir
        const data = {};
        for (let [key, value] of formData.entries()) {
            if (!key.startsWith('yetkili_kisiler') && key !== '_method') {
                data[key] = value;
            }
        }
        
        // Yetkili kişileri ekle
        if (yetkiliKisiler.length > 0) {
            data.yetkili_kisiler = yetkiliKisiler;
        }

        // Form metodu kontrol et (PUT veya POST)
        const formMethod = $('input[name="_method"]').val() || 'POST';

        // AJAX request
        $.ajax({
            url: $('#cariForm').attr('action') || '/cari',
            method: formMethod === 'PUT' ? 'PUT' : 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    // Başarılı mesajı göster
                    $('#alertBox')
                        .removeClass('alert-error')
                        .addClass('alert-success')
                        .text(response.message)
                        .fadeIn();
                    
                    // Scroll to top
                    $('html, body').animate({ scrollTop: 0 }, 300);
                    
                    // 1.5 saniye sonra listeye yönlendir
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                } else {
                    $btn.prop('disabled', false).html(originalText);
                    $('#alertBox')
                        .removeClass('alert-success')
                        .addClass('alert-error')
                        .text(response.message || 'Bir hata oluştu!')
                        .fadeIn();
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalText);

                if (xhr.status === 422) {
                    // Validation hataları
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = 'Lütfen formu kontrol edin:\n';
                    
                    for (let field in errors) {
                        const $errorSpan = $(`#error-${field}`);
                        if ($errorSpan.length) {
                            $errorSpan.text(errors[field][0]).show();
                        }
                        errorMessage += '- ' + errors[field][0] + '\n';
                    }
                    
                    $('#alertBox')
                        .removeClass('alert-success')
                        .addClass('alert-error')
                        .text('Lütfen zorunlu alanları doldurun!')
                        .fadeIn();
                    
                    // İlk hataya scroll
                    const firstError = $('.error-text:visible').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 300);
                    }
                } else {
                    // Diğer hatalar
                    const message = xhr.responseJSON?.message || 'Bir hata oluştu. Lütfen tekrar deneyin.';
                    $('#alertBox')
                        .removeClass('alert-success')
                        .addClass('alert-error')
                        .text(message)
                        .fadeIn();
                }
                
                // Scroll to top to show error
                $('html, body').animate({ scrollTop: 0 }, 300);
            }
        });
    });

    // Input focus olunca hataları temizle
    $('.form-control').on('focus', function() {
        const fieldName = $(this).attr('name');
        $(`#error-${fieldName}`).hide();
        $('#alertBox').fadeOut();
    });

    // IBAN formatla
    $('#iban').on('input', function() {
        let value = $(this).val().replace(/\s/g, '');
        if (value.length > 2) {
            value = value.match(/.{1,4}/g).join(' ');
        }
        $(this).val(value.toUpperCase());
    });

    // Telefon formatla
    $('input[type="tel"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 10) {
            value = value.substring(0, 11);
        }
        $(this).val(value);
    });
});

