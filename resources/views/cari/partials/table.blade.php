@if($cariHesaplar->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Müşteri Kodu</th>
                <th>Cari Hesap Adı</th>
                <th>Cari Grubu</th>
                <th>GSM</th>
                <th>E-posta</th>
                <th>İl/İlçe</th>
                <th style="text-align: center; width: 150px;">İşlemler</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cariHesaplar as $cari)
            <tr>
                <td><span class="badge badge-primary">{{ $cari->musteri_kodu }}</span></td>
                <td><strong>{{ $cari->cari_hesap_adi }}</strong></td>
                <td>{{ $cari->cariGroup->name ?? '-' }}</td>
                <td>{{ $cari->gsm ?? '-' }}</td>
                <td>{{ $cari->eposta ?? '-' }}</td>
                <td>{{ $cari->il ? $cari->il . '/' . $cari->ilce : '-' }}</td>
                <td style="text-align: center;">
                    <div style="display: flex; gap: 5px; justify-content: center;">
                        <a href="{{ route('cari.show', $cari->id) }}" style="background: #2196F3; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;" title="Detay">
                            👁️ Detay
                        </a>
                        <a href="{{ route('cari.edit', $cari->id) }}" style="background: #FF9800; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;" title="Düzenle">
                            ✏️ Düzenle
                        </a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="empty-state" style="text-align: center; padding: 60px 20px; color: #999;">
        <div style="font-size: 80px; margin-bottom: 20px;">🔍</div>
        <h3>Sonuç bulunamadı</h3>
        <p>Arama kriterlerinize uygun cari hesap bulunamadı.</p>
    </div>
@endif

