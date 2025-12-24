<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bakım Düzenle - Servis Takip</title>
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
                <h2><i class="fas fa-edit"></i> Bakım Düzenle</h2>
                <a href="{{ route('bakim.show', $bakim->id) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Geri Dön</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('bakim.update', $bakim->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-container">
                    <div class="form-section">
                        <h3 class="section-title">Müşteri Bilgileri</h3>
                        
                        <div class="alert" style="background: #f0f0f0; border-left: 4px solid #666; padding: 15px; margin-bottom: 20px;">
                            <strong><i class="fas fa-user"></i> Müşteri:</strong> {{ $bakim->cariHesap->cari_hesap_adi }}<br>
                            <small style="color: #666;">Müşteri bilgisi değiştirilemez.</small>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Cihaz Marka</label>
                                <input type="text" name="marka" class="form-control" value="{{ old('marka', $bakim->marka) }}">
                            </div>
                            <div class="form-group">
                                <label>Cihaz Modeli</label>
                                <input type="text" name="model" class="form-control" value="{{ old('model', $bakim->model) }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">Bakım Bilgileri</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Bakım Tarihi <span class="required">*</span></label>
                                <input type="date" name="bakim_tarihi" class="form-control" 
                                    value="{{ old('bakim_tarihi', $bakim->bakim_tarihi ? $bakim->bakim_tarihi->format('Y-m-d') : '') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Bakım Periyodu <span class="required">*</span></label>
                                <select name="periyodik_bakim" class="form-control" required>
                                    <option value="Bir Kez" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == 'Bir Kez' ? 'selected' : '' }}>Bir Kez</option>
                                    <option value="1 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '1 Aylık' ? 'selected' : '' }}>1 Aylık</option>
                                    <option value="2 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '2 Aylık' ? 'selected' : '' }}>2 Aylık</option>
                                    <option value="3 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '3 Aylık' ? 'selected' : '' }}>3 Aylık</option>
                                    <option value="4 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '4 Aylık' ? 'selected' : '' }}>4 Aylık</option>
                                    <option value="5 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '5 Aylık' ? 'selected' : '' }}>5 Aylık</option>
                                    <option value="6 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '6 Aylık' ? 'selected' : '' }}>6 Aylık</option>
                                    <option value="7 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '7 Aylık' ? 'selected' : '' }}>7 Aylık</option>
                                    <option value="8 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '8 Aylık' ? 'selected' : '' }}>8 Aylık</option>
                                    <option value="9 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '9 Aylık' ? 'selected' : '' }}>9 Aylık</option>
                                    <option value="10 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '10 Aylık' ? 'selected' : '' }}>10 Aylık</option>
                                    <option value="11 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '11 Aylık' ? 'selected' : '' }}>11 Aylık</option>
                                    <option value="12 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '12 Aylık' ? 'selected' : '' }}>12 Aylık (Yıllık)</option>
                                    <option value="13 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '13 Aylık' ? 'selected' : '' }}>13 Aylık</option>
                                    <option value="14 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '14 Aylık' ? 'selected' : '' }}>14 Aylık</option>
                                    <option value="15 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '15 Aylık' ? 'selected' : '' }}>15 Aylık</option>
                                    <option value="16 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '16 Aylık' ? 'selected' : '' }}>16 Aylık</option>
                                    <option value="17 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '17 Aylık' ? 'selected' : '' }}>17 Aylık</option>
                                    <option value="18 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '18 Aylık' ? 'selected' : '' }}>18 Aylık</option>
                                    <option value="19 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '19 Aylık' ? 'selected' : '' }}>19 Aylık</option>
                                    <option value="20 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '20 Aylık' ? 'selected' : '' }}>20 Aylık</option>
                                    <option value="21 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '21 Aylık' ? 'selected' : '' }}>21 Aylık</option>
                                    <option value="22 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '22 Aylık' ? 'selected' : '' }}>22 Aylık</option>
                                    <option value="23 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '23 Aylık' ? 'selected' : '' }}>23 Aylık</option>
                                    <option value="24 Aylık" {{ old('periyodik_bakim', $bakim->periyodik_bakim) == '24 Aylık' ? 'selected' : '' }}>24 Aylık (2 Yıllık)</option>
                                </select>
                                <small style="color: #666; display: block; margin-top: 5px;">
                                    <i class="fas fa-info-circle"></i> Periyodu değiştirdiğinizde bakım tarihi otomatik olarak yeniden hesaplanacaktır.
                                </small>
                            </div>
                        </div>

                        <div class="form-row single-column">
                            <div class="form-group">
                                <label>Bakım İçeriği</label>
                                <textarea name="bakim_icerigi" class="form-control" rows="3" placeholder="Yapılacak bakım işlemleri...">{{ old('bakim_icerigi', $bakim->bakim_icerigi) }}</textarea>
                            </div>
                        </div>

                        <div class="form-row single-column">
                            <div class="form-group">
                                <label>Bakım Lokasyonu</label>
                                <input type="text" name="bakim_lokasyonu" class="form-control" 
                                    value="{{ old('bakim_lokasyonu', $bakim->bakim_lokasyonu) }}" 
                                    placeholder="Örn: Ana Bina - 2. Kat">
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">SMS Hatırlatma</h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="sms_hatirlatma" value="1" 
                                        {{ old('sms_hatirlatma', $bakim->sms_hatirlatma) ? 'checked' : '' }} 
                                        style="width: auto; cursor: pointer;" id="smsCheckbox">
                                    <span>SMS Hatırlatma Gönder</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label>Hatırlatma Zamanı</label>
                                <select name="hatirlatma_zamani" class="form-control" id="hatirlatmaZamani">
                                    <option value="">Seçiniz...</option>
                                    <option value="1 Gün Önce" {{ old('hatirlatma_zamani', $bakim->hatirlatma_zamani) == '1 Gün Önce' ? 'selected' : '' }}>1 Gün Önce</option>
                                    <option value="3 Gün Önce" {{ old('hatirlatma_zamani', $bakim->hatirlatma_zamani) == '3 Gün Önce' ? 'selected' : '' }}>3 Gün Önce</option>
                                    <option value="1 Hafta Önce" {{ old('hatirlatma_zamani', $bakim->hatirlatma_zamani) == '1 Hafta Önce' ? 'selected' : '' }}>1 Hafta Önce</option>
                                    <option value="2 Hafta Önce" {{ old('hatirlatma_zamani', $bakim->hatirlatma_zamani) == '2 Hafta Önce' ? 'selected' : '' }}>2 Hafta Önce</option>
                                    <option value="1 Ay Önce" {{ old('hatirlatma_zamani', $bakim->hatirlatma_zamani) == '1 Ay Önce' ? 'selected' : '' }}>1 Ay Önce</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Değişiklikleri Kaydet
                        </button>
                        <a href="{{ route('bakim.show', $bakim->id) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> İptal
                        </a>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Başlangıç tarihini backend'den al
        const baseTarih = '{{ $bakim->ilk_bakim_tarihi ? $bakim->ilk_bakim_tarihi->format("Y-m-d") : ($bakim->tamamlanma_tarihi ? $bakim->tamamlanma_tarihi->format("Y-m-d") : now()->format("Y-m-d")) }}';
        
        $(document).ready(function() {
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

            // SMS checkbox kontrolü
            $('#smsCheckbox').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#hatirlatmaZamani').prop('disabled', false);
                } else {
                    $('#hatirlatmaZamani').prop('disabled', true).val('');
                }
            });

            // Sayfa yüklendiğinde kontrol et
            if (!$('#smsCheckbox').is(':checked')) {
                $('#hatirlatmaZamani').prop('disabled', true);
            }

            // Periyot değiştiğinde bakım tarihini otomatik hesapla
            $('select[name="periyodik_bakim"]').on('change', function() {
                const newPeriyot = $(this).val();
                
                // Başlangıç tarihinden (ilk bakım tarihi) yeni periyoda göre hesapla
                let newDate = new Date(baseTarih);
                
                // Periyoda göre tarihi hesapla (başlangıç tarihine ekle)
                // "X Aylık" formatını parse et (örn: "8 Aylık" -> 8)
                const monthsMatch = newPeriyot.match(/^(\d+)\s*Aylık/i);
                if (monthsMatch) {
                    const months = parseInt(monthsMatch[1]);
                    newDate.setMonth(newDate.getMonth() + months);
                } else {
                    // Özel durumlar
                    switch(newPeriyot) {
                        case 'Bir Kez':
                            // Bir kez ise başlangıç tarihini koru
                            break;
                        case 'Aylık':
                            newDate.setMonth(newDate.getMonth() + 1);
                            break;
                        case 'Yıllık':
                            newDate.setFullYear(newDate.getFullYear() + 1);
                            break;
                        case '2 Yıllık':
                            newDate.setFullYear(newDate.getFullYear() + 2);
                            break;
                    }
                }
                
                // Tarihi input'a yaz (YYYY-MM-DD formatında)
                const formattedDate = newDate.toISOString().split('T')[0];
                $('input[name="bakim_tarihi"]').val(formattedDate);
                
                // Uyarı göster
                $('#periyotWarning').remove();
                $('input[name="bakim_tarihi"]').parent().append(
                    '<small id="periyotWarning" style="color: #2196f3; display: block; margin-top: 5px;">' +
                    '<i class="fas fa-info-circle"></i> Bakım tarihi başlangıç tarihinden yeni periyoda göre hesaplandı.' +
                    '</small>'
                );
                
                setTimeout(function() {
                    $('#periyotWarning').fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            });
        });
    </script>
</body>
</html>

