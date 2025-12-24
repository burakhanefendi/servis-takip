<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bakım Ekle - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></button>
            <h1><i class="fas fa-tools"></i> Servis Takip Sistemi</h1>
        </div>
        <div class="header-right">
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name ?? 'Kullanıcı' }}</div>
                <div class="user-email">{{ Auth::user()->email }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn-logout">Çıkış Yap</button>
            </form>
        </div>
    </header>

    <div class="layout">
        @include('layouts.sidebar')

        <main class="main-content">
            <div class="content-header">
                <h2><i class="fas fa-clipboard-list"></i> Manuel Bakım Ekle</h2>
                <a href="{{ route('bakim.index') }}" class="btn btn-secondary">⬅ Bakım Listesi</a>
            </div>

            <div class="alert alert-error" id="alertBox" style="display: none;"></div>
            <div class="alert alert-success" id="successBox" style="display: none;"></div>

            <form id="bakimForm" action="{{ route('bakim.store') }}" method="POST">
                @csrf
                
                <div class="form-container">
                    <div class="form-section">
                        <h3 class="section-title">Cari ve Cihaz Bilgileri</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Cari Hesap Tanımı <span class="required">*</span></label>
                                <input type="text" id="cariSearch" class="form-control" placeholder="Örn. Uğur Bilgisayar" autocomplete="off">
                                <input type="hidden" name="cari_hesap_id" id="cariHesapId" required>
                                <div id="cariSuggestions" class="autocomplete-suggestions"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Cihaz Marka <span class="required">*</span></label>
                                <input type="text" name="marka" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Cihaz Modeli <span class="required">*</span></label>
                                <input type="text" name="model" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Bakım Bilgileri</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>İlk Bakım Tarihi <span class="required">*</span></label>
                                <input type="datetime-local" name="ilk_bakim_tarihi" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Bakım Periyodu <span class="required">*</span></label>
                                <select name="periyodik_bakim" class="form-control" required>
                                    <option value="Bir Kez" selected>Bir Kez</option>
                                    <option value="1 Aylık">1 Aylık</option>
                                    <option value="2 Aylık">2 Aylık</option>
                                    <option value="3 Aylık">3 Aylık</option>
                                    <option value="4 Aylık">4 Aylık</option>
                                    <option value="5 Aylık">5 Aylık</option>
                                    <option value="6 Aylık">6 Aylık</option>
                                    <option value="7 Aylık">7 Aylık</option>
                                    <option value="8 Aylık">8 Aylık</option>
                                    <option value="9 Aylık">9 Aylık</option>
                                    <option value="10 Aylık">10 Aylık</option>
                                    <option value="11 Aylık">11 Aylık</option>
                                    <option value="12 Aylık">12 Aylık (Yıllık)</option>
                                    <option value="13 Aylık">13 Aylık</option>
                                    <option value="14 Aylık">14 Aylık</option>
                                    <option value="15 Aylık">15 Aylık</option>
                                    <option value="16 Aylık">16 Aylık</option>
                                    <option value="17 Aylık">17 Aylık</option>
                                    <option value="18 Aylık">18 Aylık</option>
                                    <option value="19 Aylık">19 Aylık</option>
                                    <option value="20 Aylık">20 Aylık</option>
                                    <option value="21 Aylık">21 Aylık</option>
                                    <option value="22 Aylık">22 Aylık</option>
                                    <option value="23 Aylık">23 Aylık</option>
                                    <option value="24 Aylık">24 Aylık (2 Yıllık)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>İlgili Personel</label>
                                <input type="text" name="personel" class="form-control" placeholder="Teknisyen adı">
                            </div>
                        </div>

                        <div class="form-row single-column">
                            <div class="form-group">
                                <label>Bakım İçeriği</label>
                                <textarea name="bakim_icerigi" class="form-control" rows="3" placeholder="Bakım, Kontrol, Test, Parça Değişimi"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Lokasyon Bilgisi</h3>
                        
                        <div class="form-row single-column">
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; margin-bottom: 10px;">
                                    <input type="checkbox" id="farkliLokasyon" style="width: auto; cursor: pointer;">
                                    <span>Bakım Lokasyonu (Bakım verilecek adres müşterinin adresinden farklıysa lütfen buraya belirtiniz)</span>
                                </label>
                                <textarea name="bakim_lokasyonu" id="bakimLokasyonu" class="form-control" rows="3" placeholder="Farklı adres giriniz..." disabled></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Müşteri Bilgilendirme</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="sms_hatirlatma" value="1" id="smsCheck" style="width: auto; cursor: pointer;">
                                    <span>SMS ile bilgilendirme</span>
                                </label>
                            </div>
                        </div>

                        <div id="hatirlatmaZamani" style="display: none;">
                            <h4 style="margin: 15px 0 10px; font-size: 14px; color: #666;">Hatırlatma Zamanı</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Tarih</label>
                                    <input type="date" id="hatirlatmaTarih" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Saat</label>
                                    <input type="time" id="hatirlatmaSaat" class="form-control" value="09:00">
                                </div>
                            </div>
                            <input type="hidden" name="hatirlatma_zamani" id="hatirlatmaZamaniHidden">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-check"></i> Oluştur
                        </button>
                        <a href="{{ route('bakim.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Listele
                        </a>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

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

            // Cari arama (autocomplete)
            let searchTimeout;
            $('#cariSearch').on('input', function() {
                clearTimeout(searchTimeout);
                const search = $(this).val();
                
                if (search.length < 3) {
                    $('#cariSuggestions').hide();
                    return;
                }
                
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("api.cari.search") }}',
                        method: 'GET',
                        data: { search: search },
                        success: function(data) {
                            if (data.length > 0) {
                                let html = '';
                                data.forEach(function(cari) {
                                    html += `
                                        <div class="suggestion-item" data-id="${cari.id}" data-name="${cari.cari_hesap_adi}">
                                            <strong>${cari.cari_hesap_adi}</strong>
                                            <small>${cari.musteri_kodu}</small>
                                        </div>
                                    `;
                                });
                                $('#cariSuggestions').html(html).show();
                            } else {
                                $('#cariSuggestions').hide();
                            }
                        }
                    });
                }, 300);
            });

            // Cari seçimi
            $(document).on('click', '.suggestion-item', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                
                $('#cariSearch').val(name);
                $('#cariHesapId').val(id);
                $('#cariSuggestions').hide();
            });

            // Dışarı tıklanınca autocomplete kapat
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#cariSearch, #cariSuggestions').length) {
                    $('#cariSuggestions').hide();
                }
            });

            // Farklı lokasyon checkbox
            $('#farkliLokasyon').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#bakimLokasyonu').prop('disabled', false);
                } else {
                    $('#bakimLokasyonu').prop('disabled', true).val('');
                }
            });

            // SMS checkbox
            $('#smsCheck').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#hatirlatmaZamani').show();
                } else {
                    $('#hatirlatmaZamani').hide();
                }
            });

            // Form submit
            $('#bakimForm').on('submit', function(e) {
                e.preventDefault();

                $('.error-text').hide();
                $('#alertBox').hide();
                $('#successBox').hide();

                // SMS seçiliyse tarih-saat birleştir
                if ($('#smsCheck').is(':checked')) {
                    const tarih = $('#hatirlatmaTarih').val();
                    const saat = $('#hatirlatmaSaat').val();
                    if (tarih && saat) {
                        $('#hatirlatmaZamaniHidden').val(tarih + ' ' + saat);
                    }
                }

                const $btn = $('#btnSubmit');
                const originalText = $btn.html();
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...');

                const formData = $(this).serialize();

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#successBox')
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
                            let errorMsg = 'Lütfen formu kontrol edin:\n';
                            Object.keys(errors).forEach(function(key) {
                                errorMsg += '\n- ' + errors[key][0];
                            });
                            $('#alertBox')
                                .text(errorMsg)
                                .fadeIn();
                        } else {
                            const message = xhr.responseJSON?.message || 'Bir hata oluştu!';
                            $('#alertBox')
                                .text(message)
                                .fadeIn();
                        }
                        
                        $('html, body').animate({ scrollTop: 0 }, 300);
                    }
                });
            });

            // Bugünkü tarih varsayılan
            const today = new Date().toISOString().split('T')[0];
            $('#hatirlatmaTarih').val(today);
        });
    </script>
</body>
</html>


