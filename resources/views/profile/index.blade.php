@extends('layouts.app')

@section('title', 'Profil Ayarları')

@section('content')
<div class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Profil Ayarları</h1>
            <p class="page-subtitle">Hesap bilgilerinizi güncelleyin</p>
        </div>
    </div>

    <div class="content-wrapper">
        <div class="row">
            {{-- Başarı Mesajı --}}
            @if(session('success'))
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            {{-- İsim Güncelleme --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user"></i>
                        <span>İsim Bilgisi</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.updateName') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="name">İsim Soyisim</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $user->name) }}"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- E-posta Güncelleme --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-envelope"></i>
                        <span>E-posta Adresi</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.updateEmail') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="email">E-posta</label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email', $user->email) }}"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Güncelle
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Şifre Güncelleme --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-lock"></i>
                        <span>Şifre Değiştir</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.updatePassword') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="current_password">Mevcut Şifre</label>
                                        <input 
                                            type="password" 
                                            id="current_password" 
                                            name="current_password" 
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            required
                                        >
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password">Yeni Şifre</label>
                                        <input 
                                            type="password" 
                                            id="password" 
                                            name="password" 
                                            class="form-control @error('password') is-invalid @enderror"
                                            required
                                        >
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">En az 8 karakter</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="password_confirmation">Yeni Şifre (Tekrar)</label>
                                        <input 
                                            type="password" 
                                            id="password_confirmation" 
                                            name="password_confirmation" 
                                            class="form-control"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key"></i> Şifreyi Değiştir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border: none;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-header i {
    color: #2563eb;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #374151;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: block;
}

.btn {
    padding: 10px 20px;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-primary:hover {
    background: #1d4ed8;
}

.alert {
    padding: 12px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    position: relative;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

.btn-close {
    position: absolute;
    right: 10px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    opacity: 0.5;
}

.btn-close:hover {
    opacity: 1;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
}

.col-12 {
    flex: 0 0 100%;
    max-width: 100%;
    padding: 0 10px;
}

.col-md-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
    padding: 0 10px;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 10px;
}

@media (max-width: 768px) {
    .col-md-4,
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.form-text {
    display: block;
    margin-top: 5px;
}

.text-muted {
    color: #6b7280;
    font-size: 12px;
}
</style>
@endsection

