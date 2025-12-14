<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Ekle - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
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
                <h2>➕ Yeni Cari Hesap Ekle</h2>
            </div>

            <div class="alert alert-error" id="alertBox"></div>

            <form id="cariForm" action="{{ route('cari.store') }}" method="POST">
                @csrf
                <div class="form-container">
                    <!-- Firma Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">🏢 Firma Bilgileri</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="cari_hesap_adi" class="required">Cari Hesap Adı</label>
                                <input type="text" id="cari_hesap_adi" name="cari_hesap_adi" class="form-control" required>
                                <span class="error-text" id="error-cari_hesap_adi"></span>
                            </div>
                            <div class="form-group">
                                <label for="musteri_kodu">Müşteri Kodu</label>
                                <input type="text" id="musteri_kodu" name="musteri_kodu" class="form-control" value="{{ $musteriKodu }}" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="kisa_isim">Kısa İsim</label>
                                <input type="text" id="kisa_isim" name="kisa_isim" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="cari_group_id">Cari Grubu</label>
                                <select id="cari_group_id" name="cari_group_id" class="form-control">
                                    <option value="">Seçiniz...</option>
                                    @foreach($cariGroups as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}</option>
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
                                <input type="text" id="vergi_dairesi" name="vergi_dairesi" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="vergi_kimlik_no">Vergi/TC Kimlik No</label>
                                <input type="text" id="vergi_kimlik_no" name="vergi_kimlik_no" class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="iban">İBAN</label>
                                <input type="text" id="iban" name="iban" class="form-control" placeholder="TR00 0000 0000 0000 0000 0000 00">
                            </div>
                        </div>
                    </div>

                    <!-- Adres Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">📍 Adres Bilgileri</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="il">İl</label>
                                <select id="il" name="il" class="form-control">
                                    <option value="">İl Seçiniz...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ilce">İlçe</label>
                                <select id="ilce" name="ilce" class="form-control">
                                    <option value="">Önce İl Seçiniz...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row single-column">
                            <div class="form-group">
                                <label for="adres">Adres</label>
                                <textarea id="adres" name="adres" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Sevk Adresi -->
                    <div class="form-section">
                        <h3 class="section-title">🚚 Sevk Adresi</h3>
                        <div class="form-row single-column">
                            <div class="form-group">
                                <label for="sevk_adresi">Sevk Adresi</label>
                                <textarea id="sevk_adresi" name="sevk_adresi" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- İletişim Bilgileri -->
                    <div class="form-section">
                        <h3 class="section-title">📞 İletişim Bilgileri</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="gsm">GSM</label>
                                <input type="tel" id="gsm" name="gsm" class="form-control" placeholder="0555 555 55 55">
                            </div>
                            <div class="form-group">
                                <label for="eposta">E-posta</label>
                                <input type="email" id="eposta" name="eposta" class="form-control" placeholder="ornek@email.com">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="sabit_telefon">Sabit Telefon</label>
                                <input type="tel" id="sabit_telefon" name="sabit_telefon" class="form-control" placeholder="0212 555 55 55">
                            </div>
                        </div>
                    </div>

                    <!-- Yetkili Kişiler -->
                    <div class="form-section">
                        <h3 class="section-title">👤 Yetkili Kişiler</h3>
                        <div class="yetkili-list" id="yetkiliList">
                            <!-- Yetkili kişiler buraya eklenecek -->
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
                            💾 Kaydet
                        </button>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/turkey-cities.js') }}"></script>
    <script src="{{ asset('js/cari-form.js') }}"></script>
</body>
</html>

