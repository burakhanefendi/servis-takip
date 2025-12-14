<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Servis Ekle - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/stepper.css') }}">
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
                <h2>➕ Yeni Servis Ekle</h2>
            </div>

            <div class="alert alert-error" id="alertBox" style="display: none;"></div>

            <form id="servisForm" action="{{ route('servis.store') }}" method="POST">
                @csrf
                <input type="hidden" name="servis_no" value="{{ $servisNo }}">
                <input type="hidden" id="cari_hesap_id" name="cari_hesap_id" value="">

                <div class="stepper-container">
                    <!-- Stepper Header -->
                    <div class="stepper-header">
                        <div class="step" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Cari Hesap Bilgileri</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Ürün Bilgileri</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Detaylar</div>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Ücret ve Bilgilendirme</div>
                        </div>
                    </div>

                    <!-- Stepper Body -->
                    <div class="stepper-body">
                        <!-- Step 1: Cari Hesap Bilgileri -->
                        <div id="step1" class="step-content">
                            <h3 class="section-title">👤 Cari Hesap Bilgileri</h3>
                            
                            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                                <strong>Servis No:</strong> {{ $servisNo }}
                            </div>

                            <div class="form-row">
                                <div class="form-group autocomplete-container">
                                    <label for="cari_hesap_tanimi" class="required">Cari Hesap Tanımı</label>
                                    <input type="text" id="cari_hesap_tanimi" class="form-control" placeholder="En az 3 karakter yazın..." autocomplete="off" required>
                                    <div id="autocomplete-results" class="autocomplete-results"></div>
                                    <span class="error-text" id="error-cari_hesap_id"></span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="eposta">E-posta</label>
                                    <input type="email" id="eposta" name="eposta" class="form-control" placeholder="ornek@email.com">
                                </div>
                                <div class="form-group">
                                    <label for="gsm">GSM</label>
                                    <input type="tel" id="gsm" name="gsm" class="form-control" placeholder="0555 555 55 55">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="sabit_telefon">Sabit Telefon</label>
                                    <input type="tel" id="sabit_telefon" name="sabit_telefon" class="form-control" placeholder="0212 555 55 55">
                                </div>
                                <div class="form-group">
                                    <label for="il">İl</label>
                                    <select id="il" name="il" class="form-control">
                                        <option value="">İl Seçiniz...</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
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

                        <!-- Step 2: Ürün Bilgileri -->
                        <div id="step2" class="step-content">
                            <h3 class="section-title">📦 Ürün Bilgileri</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="marka">Marka</label>
                                    <input type="text" id="marka" name="marka" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="model">Model</label>
                                    <input type="text" id="model" name="model" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="seri_numarasi">Seri Numarası</label>
                                    <input type="text" id="seri_numarasi" name="seri_numarasi" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="urun_cinsi">Ürün Cinsi</label>
                                    <input type="text" id="urun_cinsi" name="urun_cinsi" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="model_kodu">Model Kodu</label>
                                    <input type="text" id="model_kodu" name="model_kodu" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="urun_rengi">Ürün Rengi</label>
                                    <select id="urun_rengi" name="urun_rengi" class="form-control">
                                        <option value="">Seçiniz...</option>
                                        <option value="Siyah">Siyah</option>
                                        <option value="Beyaz">Beyaz</option>
                                        <option value="Kırmızı">Kırmızı</option>
                                        <option value="Yeşil">Yeşil</option>
                                        <option value="Mavi">Mavi</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="garanti_durumu">Garanti Durumu</label>
                                    <select id="garanti_durumu" name="garanti_durumu" class="form-control">
                                        <option value="">Seçiniz...</option>
                                        <option value="Garantili">Garantili</option>
                                        <option value="Garantisiz">Garantisiz</option>
                                        <option value="Garanti Süresi Dolmuş">Garanti Süresi Dolmuş</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fatura_numarasi">Fatura Numarası</label>
                                    <input type="text" id="fatura_numarasi" name="fatura_numarasi" class="form-control">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="fatura_tarihi">Fatura Tarihi</label>
                                    <input type="date" id="fatura_tarihi" name="fatura_tarihi" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Detaylar -->
                        <div id="step3" class="step-content">
                            <h3 class="section-title">📝 Detaylar</h3>

                            <div class="form-row single-column">
                                <div class="form-group">
                                    <label for="urunun_fiziksel_durumu">Ürünün Fiziksel Durumu</label>
                                    <textarea id="urunun_fiziksel_durumu" name="urunun_fiziksel_durumu" class="form-control" placeholder="Ürünün dış görünüşü, hasarlar vs."></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="oncelik_durumu">Öncelik Durumu</label>
                                    <select id="oncelik_durumu" name="oncelik_durumu" class="form-control">
                                        <option value="Normal">Normal</option>
                                        <option value="Acil">Acil</option>
                                        <option value="Çok Acil">Çok Acil</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row single-column">
                                <div class="form-group">
                                    <label for="musterinin_sikayeti">Müşterinin Şikayeti</label>
                                    <textarea id="musterinin_sikayeti" name="musterinin_sikayeti" class="form-control" placeholder="Müşterinin bildirdiği sorun"></textarea>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="ariza_tanimi">Arıza Tanımı</label>
                                    <select id="ariza_tanimi" name="ariza_tanimi" class="form-control">
                                        <option value="">Seçiniz...</option>
                                        <option value="Filtre Değişimi">Filtre Değişimi</option>
                                        <option value="Genel Bakım">Genel Bakım</option>
                                        <option value="Montaj ve İlk Kurulum">Montaj ve İlk Kurulum</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row single-column">
                                <div class="form-group">
                                    <label for="teknisyenin_yorumu">Teknisyenin Yorumu</label>
                                    <textarea id="teknisyenin_yorumu" name="teknisyenin_yorumu" class="form-control" placeholder="Teknisyen notları"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Ücret ve Bilgilendirme -->
                        <div id="step4" class="step-content">
                            <h3 class="section-title">💰 Ücret ve Bilgilendirme</h3>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tahmini_ucret">Tahmini Ücret (TL)</label>
                                    <input type="number" id="tahmini_ucret" name="tahmini_ucret" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="teslimat_turu">Teslimat Türü</label>
                                    <select id="teslimat_turu" name="teslimat_turu" class="form-control">
                                        <option value="Elden" selected>Elden</option>
                                        <option value="Kargo">Kargo</option>
                                    </select>
                                </div>
                                <div class="form-group" id="kargo_sirket_group" style="display: none;">
                                    <label for="kargo_sirket">Kargo Şirketi</label>
                                    <select id="kargo_sirket" name="kargo_sirket" class="form-control">
                                        <option value="">Seçiniz...</option>
                                        <option value="Aras Kargo">Aras Kargo</option>
                                        <option value="MNG Kargo">MNG Kargo</option>
                                        <option value="Yurtiçi Kargo">Yurtiçi Kargo</option>
                                        <option value="PTT Kargo">PTT Kargo</option>
                                        <option value="Sürat Kargo">Sürat Kargo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="tahmini_bitis_tarihi">Tahmini Bitiş Tarihi</label>
                                    <input type="date" id="tahmini_bitis_tarihi" name="tahmini_bitis_tarihi" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="personel">Personel</label>
                                    <input type="text" id="personel" name="personel" class="form-control" placeholder="Sorumlu personel">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="randevu_tarihi">Randevu Tarihi</label>
                                    <input type="datetime-local" id="randevu_tarihi" name="randevu_tarihi" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Step Actions -->
                        <div class="step-actions">
                            <button type="button" class="btn btn-prev" id="btnPrev" style="display: none;">⬅ Geri</button>
                            <div style="flex: 1;"></div>
                            <button type="button" class="btn btn-next" id="btnNext">İleri ➡</button>
                            <button type="submit" class="btn btn-primary" id="btnSubmit" style="display: none;">💾 Kaydet</button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/turkey-cities.js') }}"></script>
    <script src="{{ asset('js/servis-form.js') }}"></script>
</body>
</html>

