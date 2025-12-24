@extends('layouts.app')

@section('title', 'Tenant Yönetimi')

@section('content')
<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 20px;">
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; background: white; padding: 20px; border-bottom: 2px solid #f0f0f0;">
            <h2 style="margin: 0; font-size: 24px; font-weight: 700; color: #2d3748;">
                <i class="fas fa-building"></i> Tenant Yönetimi
            </h2>
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary" style="padding: 10px 20px; background: #667eea; color: white; text-decoration: none; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-plus"></i> Yeni Tenant
            </a>
        </div>
        
        <div class="card-body" style="background: white; padding: 20px;">
            @if(session('success'))
                <div class="alert alert-success" style="background: #d1fae5; border-left: 4px solid #10b981; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #065f46;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger" style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 15px; margin-bottom: 20px; border-radius: 8px; color: #991b1b;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">ID</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">SUBDOMAIN</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">FİRMA ADI</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">VERİTABANI</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">DURUM</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">OLUŞTURMA</th>
                        <th style="padding: 12px; text-align: left; font-size: 12px; font-weight: 700; color: #4a5568;">İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tenants as $tenant)
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 12px;">{{ $tenant->id }}</td>
                        <td style="padding: 12px;">
                            <a href="https://{{ $tenant->subdomain }}.aritmapp.com" target="_blank" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                {{ $tenant->subdomain }}
                                <i class="fas fa-external-link-alt" style="font-size: 10px;"></i>
                            </a>
                        </td>
                        <td style="padding: 12px; font-weight: 600;">{{ $tenant->name }}</td>
                        <td style="padding: 12px; font-size: 12px; color: #718096;">{{ $tenant->database_name }}</td>
                        <td style="padding: 12px;">
                            @if($tenant->isActive())
                                <span style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    ✅ Aktif
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                                    ❌ Pasif
                                </span>
                            @endif
                        </td>
                        <td style="padding: 12px; font-size: 12px; color: #718096;">
                            {{ $tenant->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td style="padding: 12px;">
                            <form action="{{ route('admin.tenants.destroy', $tenant->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bu tenant\'ı silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #9ca3af;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px; opacity: 0.5; display: block;"></i>
                            Henüz tenant oluşturulmamış
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div style="margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 12px; border-left: 4px solid #667eea;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 10px; color: #2d3748;">
            <i class="fas fa-info-circle"></i> Yeni Tenant Ekleme Adımları
        </h3>
        <ol style="margin: 0; padding-left: 20px; color: #4a5568; line-height: 1.8;">
            <li>Yukarıdaki "Yeni Tenant" butonuna tıklayın</li>
            <li>Subdomain ve firma adını girin</li>
            <li>cPanel'den subdomain oluşturun: <code>subdomain.aritmapp.com</code></li>
            <li>Document Root: <code>/home/.../laravel-app/public</code></li>
            <li>SSL sertifikası ekleyin (AutoSSL)</li>
        </ol>
    </div>
</div>
@endsection

