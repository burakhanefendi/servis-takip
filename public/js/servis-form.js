$(document).ready(function() {
    // CSRF token ayarla
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let currentStep = 1;
    const totalSteps = 4;

    // İl ve İlçe Dropdown'larını Doldur
    function populateCities() {
        const $ilSelect = $('#il');
        
        // İlleri ekle
        turkeyCities.forEach(function(city) {
            $ilSelect.append(`<option value="${city.text}">${city.text}</option>`);
        });
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
        // Step içeriğini göster
        $('.step-content').removeClass('active');
        $(`#step${step}`).addClass('active');

        // Step header'ı güncelle
        $('.step').removeClass('active completed');
        for (let i = 1; i <= totalSteps; i++) {
            if (i < step) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            } else if (i === step) {
                $(`.step[data-step="${i}"]`).addClass('active');
            }
        }

        // Button durumlarını güncelle
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

        currentStep = step;
    }

    // İlk step'i göster
    showStep(1);

    // Next butonu
    $('#btnNext').on('click', function() {
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    });

    // Previous butonu
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

    // Cari arama autocomplete
    let searchTimeout;
    let selectedCariId = null;

    $('#cari_hesap_tanimi').on('input', function() {
        const search = $(this).val();
        
        clearTimeout(searchTimeout);

        if (search.length < 3) {
            $('#autocomplete-results').removeClass('show');
            selectedCariId = null;
            $('#cari_hesap_id').val('');
            return;
        }

        $('#autocomplete-results').html('<div class="autocomplete-loading">Aranıyor...</div>').addClass('show');

        searchTimeout = setTimeout(function() {
            $.ajax({
                url: '/api/cari/search',
                method: 'GET',
                data: { search: search },
                success: function(data) {
                    if (data.length === 0) {
                        $('#autocomplete-results').html('<div class="autocomplete-no-results">Sonuç bulunamadı</div>');
                    } else {
                        let html = '';
                        data.forEach(function(cari) {
                            html += `
                                <div class="autocomplete-item" data-id="${cari.id}" 
                                     data-eposta="${cari.eposta || ''}" 
                                     data-gsm="${cari.gsm || ''}" 
                                     data-sabit="${cari.sabit_telefon || ''}" 
                                     data-il="${cari.il || ''}" 
                                     data-ilce="${cari.ilce || ''}" 
                                     data-adres="${cari.adres || ''}">
                                    <div class="autocomplete-item-title">${cari.cari_hesap_adi}</div>
                                    <div class="autocomplete-item-code">${cari.musteri_kodu}</div>
                                    ${cari.gsm ? `<div class="autocomplete-item-details">📱 ${cari.gsm}</div>` : ''}
                                </div>
                            `;
                        });
                        $('#autocomplete-results').html(html);
                    }
                },
                error: function() {
                    $('#autocomplete-results').html('<div class="autocomplete-no-results">Bir hata oluştu</div>');
                }
            });
        }, 300);
    });

    // Cari seçimi
    $(document).on('click', '.autocomplete-item', function() {
        selectedCariId = $(this).data('id');
        
        // Hidden input'a ID'yi kaydet
        $('#cari_hesap_id').val(selectedCariId);
        
        // Cari bilgilerini formu doldur
        $('#cari_hesap_tanimi').val($(this).find('.autocomplete-item-title').text());
        $('#eposta').val($(this).data('eposta'));
        $('#gsm').val($(this).data('gsm'));
        $('#sabit_telefon').val($(this).data('sabit'));
        
        // İl ve ilçe için
        const ilValue = $(this).data('il');
        const ilceValue = $(this).data('ilce');
        
        if (ilValue) {
            $('#il').val(ilValue);
            // İlçeleri doldur ve seç
            populateDistricts(ilValue, ilceValue);
        }
        
        $('#adres').val($(this).data('adres'));
        
        // Autocomplete'i kapat
        $('#autocomplete-results').removeClass('show');
    });

    // Autocomplete dışına tıklayınca kapat
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.autocomplete-container').length) {
            $('#autocomplete-results').removeClass('show');
        }
    });

    // Teslimat türü değiştiğinde
    $('#teslimat_turu').on('change', function() {
        if ($(this).val() === 'Elden') {
            $('#kargo_sirket_group').hide();
            $('#kargo_sirket').val('');
        } else {
            $('#kargo_sirket_group').show();
        }
    });

    // Form submit
    $('#servisForm').on('submit', function(e) {
        e.preventDefault();

        // Validasyon
        if (!selectedCariId) {
            alert('Lütfen bir cari hesap seçin!');
            showStep(1);
            return;
        }

        $('.error-text').hide();
        $('#alertBox').hide();

        const $btn = $('#btnSubmit');
        const originalText = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner"></span> Kaydediliyor...');

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
                    for (let field in errors) {
                        $(`#error-${field}`).text(errors[field][0]).show();
                    }
                    $('#alertBox')
                        .removeClass('alert-success')
                        .addClass('alert-error')
                        .text('Lütfen zorunlu alanları doldurun!')
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

    // Input focus olunca hataları temizle
    $('.form-control').on('focus', function() {
        const fieldName = $(this).attr('name');
        $(`#error-${fieldName}`).hide();
        $('#alertBox').fadeOut();
    });
});

