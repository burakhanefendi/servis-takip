<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Düzenle - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">☰</button>
            <h1>💧 Servis Takip Sistemi</h1>
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
                <h2>✏️ Cari Hesap Düzenle</h2>
                <a href="{{ route('cari.index') }}" class="btn btn-secondary">⬅ Geri Dön</a>
            </div>

            <div class="alert alert-error" id="alertBox" style="display: none;"></div>

            <form id="cariForm" action="{{ route('cari.update', $cari->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-container">
                    <!-- Firma Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">🏢 Firma Bilgileri</h3>
                        
                        <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <strong>Müşteri Kodu:</strong> {{ $cari->musteri_kodu }}
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="cari_hesap_adi" class="required">Cari Hesap Adı</label>
                                <input type="text" id="cari_hesap_adi" name="cari_hesap_adi" class="form-control" value="{{ $cari->cari_hesap_adi }}" required>
                                <span class="error-text" id="error-cari_hesap_adi"></span>
                            </div>
                            <div class="form-group">
                                <label for="kisa_isim">Kısa İsim</label>
                                <input type="text" id="kisa_isim" name="kisa_isim" class="form-control" value="{{ $cari->kisa_isim }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cari_group_id">Cari Grubu</label>
                                <select id="cari_group_id" name="cari_group_id" class="form-control">
                                    <option value="">Seçiniz...</option>
                                    @foreach($cariGroups as $group)
                                        <option value="{{ $group->id }}" {{ $cari->cari_group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Vergi Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">📄 Vergi Bilgileri</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="vergi_dairesi">Vergi Dairesi</label>
                                <input type="text" id="vergi_dairesi" name="vergi_dairesi" class="form-control" value="{{ $cari->vergi_dairesi }}">
                            </div>
                            <div class="form-group">
                                <label for="vergi_kimlik_no">Vergi/TC Kimlik No</label>
                                <input type="text" id="vergi_kimlik_no" name="vergi_kimlik_no" class="form-control" value="{{ $cari->vergi_kimlik_no }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="iban">İBAN</label>
                                <input type="text" id="iban" name="iban" class="form-control" value="{{ $cari->iban }}">
                            </div>
                        </div>
                    </div>

                    <!-- Adres Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">📍 Adres Bilgileri</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="il">İl</label>
                                <select id="il" name="il" class="form-control" data-selected="{{ $cari->il }}">
                                    <option value="">İl Seçiniz...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ilce">İlçe</label>
                                <select id="ilce" name="ilce" class="form-control" data-selected="{{ $cari->ilce }}">
                                    <option value="">Önce İl Seçiniz...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row single-column">
                            <div class="form-group">
                                <label for="adres">Adres</label>
                                <textarea id="adres" name="adres" class="form-control">{{ $cari->adres }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Sevk Adresi -->
                    <div class="form-section">
                        <h3 class="section-title">🚚 Sevk Adresi</h3>
                        <div class="form-row single-column">
                            <div class="form-group">
                                <label for="sevk_adresi">Sevk Adresi</label>
                                <textarea id="sevk_adresi" name="sevk_adresi" class="form-control">{{ $cari->sevk_adresi }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">📞 İletişim Bilgileri</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="gsm">GSM</label>
                                <input type="tel" id="gsm" name="gsm" class="form-control" value="{{ $cari->gsm }}">
                            </div>
                            <div class="form-group">
                                <label for="eposta">E-posta</label>
                                <input type="email" id="eposta" name="eposta" class="form-control" value="{{ $cari->eposta }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="sabit_telefon">Sabit Telefon</label>
                                <input type="tel" id="sabit_telefon" name="sabit_telefon" class="form-control" value="{{ $cari->sabit_telefon }}">
                            </div>
                        </div>
                    </div>

                    <!-- Yetkili Kişiler -->
                    <div class="form-section">
                        <h3 class="section-title">👤 Yetkili Kişiler</h3>
                        <div class="yetkili-list" id="yetkiliList">
                            @foreach($cari->yetkiliKisiler as $index => $yetkili)
                            <div class="yetkili-item" data-index="{{ $index + 1 }}">
                                <div class="yetkili-item-header">
                                    <span class="yetkili-item-title">Yetkili Kişi {{ $index + 1 }}</span>
                                    <button type="button" class="btn-remove-yetkili" data-index="{{ $index + 1 }}">🗑️ Sil</button>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Ad Soyad</label>
                                        <input type="text" name="yetkili_kisiler[{{ $index + 1 }}][ad_soyad]" class="form-control" value="{{ $yetkili->ad_soyad }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Ünvan</label>
                                        <input type="text" name="yetkili_kisiler[{{ $index + 1 }}][unvan]" class="form-control" value="{{ $yetkili->unvan }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Telefon</label>
                                        <input type="tel" name="yetkili_kisiler[{{ $index + 1 }}][telefon]" class="form-control" value="{{ $yetkili->telefon }}">
                                    </div>
                                    <div class="form-group">
                                        <label>E-posta</label>
                                        <input type="email" name="yetkili_kisiler[{{ $index + 1 }}][eposta]" class="form-control" value="{{ $yetkili->eposta }}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Dahili</label>
                                        <input type="text" name="yetkili_kisiler[{{ $index + 1 }}][dahili]" class="form-control" value="{{ $yetkili->dahili }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add-yetkili" id="btnAddYetkili">
                            ➕ Yetkili Kişi Ekle
                        </button>
                    </div>

                    <!-- Form Buttons -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('cari.index') }}'">
                            ❌ İptal
                        </button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            💾 Güncelle
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/turkey-cities.js') }}"></script>
    <script src="{{ asset('js/cari-form.js') }}"></script>
    <script>
        // Yetkili kişi counter'ı başlat
        let yetkiliCounter = {{ $cari->yetkiliKisiler->count() }};
    </script>
</body>
</html>

