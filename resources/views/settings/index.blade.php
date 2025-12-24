@extends('layouts.app')

@section('title', 'Sistem Ayarları')

@push('styles')
<style>
    .settings-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 30px;
    }
    
    .settings-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 35px;
        margin-bottom: 25px;
        transition: transform 0.2s;
    }
    
    .settings-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.12);
    }
    
    .settings-header {
        margin-bottom: 35px;
    }
    
    .settings-title {
        font-size: 28px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .settings-title i {
        color: #667eea;
    }
    
    .settings-subtitle {
        color: #718096;
        font-size: 15px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .section-title i {
        color: #667eea;
        font-size: 20px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-grid.full {
        grid-template-columns: 1fr;
    }
    
    .form-group {
        margin-bottom: 0;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s;
        background: #f8fafc;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .form-group textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }
    
    .logo-upload {
        border: 3px dashed #cbd5e0;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8fafc;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    .logo-upload:hover {
        border-color: #667eea;
        background: #f0f4ff;
    }
    
    .logo-upload.has-image {
        border-style: solid;
        border-color: #667eea;
        background: white;
    }
    
    .upload-icon {
        font-size: 48px;
        color: #667eea;
        margin-bottom: 15px;
    }
    
    .upload-text {
        color: #718096;
        font-size: 14px;
    }
    
    .upload-text strong {
        color: #2d3748;
        display: block;
        margin-bottom: 5px;
        font-size: 15px;
    }
    
    .logo-preview {
        max-width: 250px;
        max-height: 150px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .current-logo-info {
        text-align: center;
        margin-top: 15px;
        color: #718096;
        font-size: 13px;
    }
    
    .btn-save {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 14px 40px;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .btn-save i {
        font-size: 18px;
    }
    
    .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
        animation: slideDown 0.3s ease;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .alert-danger {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
    
    .alert i {
        font-size: 18px;
    }
    
    .alert ul {
        margin: 5px 0 0 0;
        padding-left: 20px;
    }
    
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .settings-container {
            padding: 15px;
        }
        
        .settings-card {
            padding: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <h1 class="settings-title">
            <i class="fas fa-cog"></i>
            Sistem Ayarları
        </h1>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success" style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger" style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-exclamation-circle"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif
    
    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
    
    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
        @csrf
        
        <!-- Logo -->
        <div class="settings-card">
            <div class="section-title">
                <i class="fas fa-image"></i>
                Firma Logosu
            </div>
            
            <div class="logo-upload {{ ($settings && $settings->logo) ? 'has-image' : '' }}" onclick="document.getElementById('logoInput').click()">
                @if($settings && $settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="Logo" class="logo-preview" id="logoPreview">
                    <div class="upload-text" style="margin-top: 15px;">
                        <small style="color: #999;">Değiştirmek için tıklayın</small>
                    </div>
                @else
                    <div class="upload-icon" id="uploadIcon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text" id="uploadText">
                        <strong>Logo yüklemek için tıklayın</strong><br>
                        PNG, JPG, GIF, WebP, SVG - Maksimum 5MB
                    </div>
                    <img src="" alt="Logo Preview" class="logo-preview" id="logoPreview" style="display: none;">
                @endif
            </div>
            <input type="file" id="logoInput" name="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp,image/svg+xml" style="display: none;">
            @if($settings && $settings->logo)
            <div class="current-logo-info">
                Mevcut Logo: {{ basename($settings->logo) }}
            </div>
            @endif
        </div>
        
        <!-- Firma Bilgileri -->
        <div class="settings-card">
            <div class="section-title">
                <i class="fas fa-building"></i>
                Firma Bilgileri
            </div>
            
            <div class="form-group">
                <label>Firma Adı <span style="color: red;">*</span></label>
                <input type="text" name="firma_adi" value="{{ old('firma_adi', $settings->firma_adi ?? '') }}" placeholder="Firma adını giriniz" required>
            </div>
        </div>
        
        <!-- İletişim Bilgileri -->
        <div class="settings-card">
            <div class="section-title">
                <i class="fas fa-phone"></i>
                İletişim Bilgileri
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Telefon 1</label>
                    <input type="text" name="telefon_1" value="{{ old('telefon_1', $settings->telefon_1 ?? '') }}" placeholder="0xxx xxx xx xx">
                </div>
                
                <div class="form-group">
                    <label>Telefon 2</label>
                    <input type="text" name="telefon_2" value="{{ old('telefon_2', $settings->telefon_2 ?? '') }}" placeholder="0xxx xxx xx xx">
                </div>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>E-posta</label>
                    <input type="email" name="email" value="{{ old('email', $settings->email ?? '') }}" placeholder="info@firma.com">
                </div>
                
                <div class="form-group">
                    <label>Website</label>
                    <input type="text" name="website" value="{{ old('website', $settings->website ?? '') }}" placeholder="www.firma.com">
                </div>
            </div>
        </div>
        
        <!-- Adres Bilgileri -->
        <div class="settings-card">
            <div class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                Adres Bilgileri
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>İl</label>
                    <input type="text" name="il" value="{{ old('il', $settings->il ?? '') }}" placeholder="İl">
                </div>
                
                <div class="form-group">
                    <label>İlçe</label>
                    <input type="text" name="ilce" value="{{ old('ilce', $settings->ilce ?? '') }}" placeholder="İlçe">
                </div>
            </div>
            
            <div class="form-grid full">
                <div class="form-group">
                    <label>Adres</label>
                    <textarea name="adres" placeholder="Mahalle, cadde, sokak, no...">{{ old('adres', $settings->adres ?? '') }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Kaydet Butonu -->
        <div style="text-align: center; margin-top: 35px;">
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i>
                Ayarları Kaydet
            </button>
        </div>
    </form>
</div><!-- /settings-container -->
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoInput = document.getElementById('logoInput');
        const logoPreview = document.getElementById('logoPreview');
        const uploadIcon = document.getElementById('uploadIcon');
        const uploadText = document.getElementById('uploadText');
        const logoUpload = document.querySelector('.logo-upload');
        
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Dosya tipini kontrol et
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Lütfen geçerli bir resim dosyası seçin (JPG, PNG, GIF, WebP, SVG)');
                    logoInput.value = '';
                    return;
                }
                
                // Dosya boyutunu kontrol et (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Dosya boyutu 5MB\'dan küçük olmalıdır');
                    logoInput.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    logoPreview.src = e.target.result;
                    logoPreview.style.display = 'block';
                    logoUpload.classList.add('has-image');
                    
                    if (uploadIcon) uploadIcon.style.display = 'none';
                    if (uploadText) {
                        uploadText.innerHTML = '<small style="color: #667eea; font-weight: 600;">✓ Yeni logo seçildi - Kaydetmeyi unutmayın!</small>';
                    }
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Form gönderilmeden önce bilgilendirme
        const settingsForm = document.getElementById('settingsForm');
        settingsForm.addEventListener('submit', function(e) {
            const submitBtn = settingsForm.querySelector('.btn-save');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Kaydediliyor...';
        });
    });
</script>
@endpush
