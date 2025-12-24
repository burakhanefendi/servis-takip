@extends('layouts.app')

@section('title', 'Teklif Listesi')

@push('styles')
<style>
    .teklif-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .btn-primary {
        background: #667eea;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .btn-primary:hover {
        background: #5568d3;
        transform: translateY(-1px);
    }
    
    .teklif-table {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
    }
    
    thead {
        background: #f8fafc;
    }
    
    th {
        text-align: left;
        padding: 15px 20px;
        font-weight: 600;
        color: #4a5568;
        border-bottom: 2px solid #e2e8f0;
        font-size: 13px;
        text-transform: uppercase;
        white-space: nowrap;
    }
    
    td {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        color: #2d3748;
        font-size: 14px;
    }
    
    tr:hover {
        background: #f9fafb;
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .status-taslak {
        background: #e0e7ff;
        color: #4338ca;
    }
    
    .status-gonderildi {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .status-onaylandi {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-reddedildi {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .actions {
        display: flex;
        gap: 8px;
    }
    
    .btn-icon {
        padding: 6px 10px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .btn-icon:hover {
        transform: translateY(-1px);
    }
    
    .btn-view {
        background: #dbeafe;
        color: #1e40af;
    }
    
    .btn-edit {
        background: #fef3c7;
        color: #92400e;
    }
    
    .btn-delete {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #9ca3af;
    }
    
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.5;
    }
    
    .empty-state h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #6b7280;
    }
    
    .empty-state p {
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        margin-top: 20px;
        padding: 20px;
    }
    
    .pagination a,
    .pagination span {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        color: #4a5568;
        transition: all 0.2s;
    }
    
    .pagination a:hover {
        background: #f0f0f0;
    }
    
    .pagination .active {
        background: #667eea;
        color: white;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="teklif-container">
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-file-invoice"></i>
            Teklif Listesi
        </h1>
        <a href="{{ route('teklif.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i>
            Yeni Teklif Oluştur
        </a>
    </div>
    
    <div class="teklif-table">
        @if($teklifler->count() > 0)
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>TEKLİF NO</th>
                        <th>MÜŞTERİ</th>
                        <th>BAŞLIK</th>
                        <th>TARİH</th>
                        <th>GEÇERLİLİK</th>
                        <th>TUTAR</th>
                        <th>DURUM</th>
                        <th>İŞLEMLER</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teklifler as $teklif)
                    <tr>
                        <td><strong>{{ $teklif->teklif_no }}</strong></td>
                        <td>{{ $teklif->cariHesap->cari_hesap_adi ?? '-' }}</td>
                        <td>{{ $teklif->baslik ?? '-' }}</td>
                        <td>{{ $teklif->baslangic_tarihi->format('d.m.Y') }}</td>
                        <td>{{ $teklif->gecerlilik_tarihi->format('d.m.Y') }}</td>
                        <td>
                            @php
                                $symbol = match($teklif->para_birimi) {
                                    'USD' => '$',
                                    'EUR' => '€',
                                    'GBP' => '£',
                                    default => '₺'
                                };
                            @endphp
                            <strong>{{ $symbol }}{{ number_format($teklif->genel_toplam, 2) }}</strong>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $teklif->durum }}">
                                {{ ucfirst($teklif->durum) }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('teklif.show', $teklif->id) }}" class="btn-icon btn-view" title="Görüntüle">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('teklif.edit', $teklif->id) }}" class="btn-icon btn-edit" title="Düzenle">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn-icon btn-delete" onclick="deleteTeklif({{ $teklif->id }})" title="Sil">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination">
            {{ $teklifler->links() }}
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-file-invoice"></i>
            <h3>Henüz teklif oluşturulmadı</h3>
            <p>İlk teklifinizi oluşturmak için yukarıdaki butona tıklayın</p>
            <a href="{{ route('teklif.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i>
                İlk Teklifi Oluştur
            </a>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteTeklif(id) {
    if (!confirm('Bu teklifi silmek istediğinizden emin misiniz?')) {
        return;
    }
    
    $.ajax({
        url: '/teklif/' + id,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                alert(response.message);
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Hata: ' + (xhr.responseJSON?.message || 'Bir hata oluştu.'));
        }
    });
}
</script>
@endpush

