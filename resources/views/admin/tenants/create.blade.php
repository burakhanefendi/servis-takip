@extends('layouts.app')

@section('title', 'Yeni Tenant Oluştur')

@section('content')
<div class="container" style="max-width: 800px; margin: 0 auto; padding: 20px;">
    <div class="card" style="background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
        <div class="card-header" style="padding: 20px; border-bottom: 2px solid #f0f0f0;">
            <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #2d3748;">
                <i class="fas fa-plus-circle"></i> Yeni Tenant Oluştur
            </h2>
        </div>
        
        <div class="card-body" style="padding: 30px;">
            @if($errors->any())
                <div class="alert alert-danger" style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #991b1b;">
                    <strong><i class="fas fa-exclamation-circle"></i> Hata:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('admin.tenants.store') }}" method="POST">
                @csrf
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #4a5568;">
                        Subdomain <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="text" 
                               name="subdomain" 
                               value="{{ old('subdomain') }}"
                               placeholder="kapakli"
                               required
                               style="flex: 1; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <span style="color: #718096; font-weight: 600;">.aritmapp.com</span>
                    </div>
                    <small style="color: #718096; font-size: 12px;">Sadece harf ve rakam kullanın (boşluk yok)</small>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #4a5568;">
                        Firma Adı <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           placeholder="Kapaklı Su Arıtma"
                           required
                           style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #4a5568;">
                        Abonelik Bitiş Tarihi (Opsiyonel)
                    </label>
                    <input type="date" 
                           name="subscription_expires" 
                           value="{{ old('subscription_expires') }}"
                           style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <small style="color: #718096; font-size: 12px;">Boş bırakılırsa süresiz olur</small>
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: #4a5568;">
                        Notlar (Opsiyonel)
                    </label>
                    <textarea name="notes" 
                              rows="3"
                              placeholder="İç notlar, özel bilgiler vb."
                              style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">{{ old('notes') }}</textarea>
                </div>
                
                <div style="margin-top: 30px; display: flex; gap: 15px;">
                    <a href="{{ route('admin.tenants.index') }}" 
                       style="flex: 1; padding: 12px 24px; background: #6b7280; color: white; text-align: center; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-arrow-left"></i> Geri
                    </a>
                    <button type="submit" 
                            style="flex: 2; padding: 12px 24px; background: #667eea; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-save"></i> Tenant Oluştur
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div style="margin-top: 30px; padding: 20px; background: #fef3c7; border-radius: 12px; border-left: 4px solid #f59e0b;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 10px; color: #92400e;">
            <i class="fas fa-exclamation-triangle"></i> Önemli!
        </h3>
        <p style="margin: 0; color: #78350f; line-height: 1.6;">
            Tenant oluşturduktan sonra <strong>cPanel'den subdomain eklemeyi unutmayın!</strong><br>
            Document Root: <code>/home/kullanici/public_html/laravel-app/public</code>
        </p>
    </div>
</div>
@endsection

