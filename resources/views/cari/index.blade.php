<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Listesi - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        thead th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: all 0.3s;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        tbody td {
            padding: 15px 20px;
            font-size: 14px;
            color: #333;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: #e3f2fd;
            color: #1976d2;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .btn-new {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-new:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        #searchInput:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .pagination-link:hover {
            background: #5568d3 !important;
            color: white !important;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            thead th, tbody td {
                padding: 12px 10px;
                font-size: 12px;
            }
        }
    </style>
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
                <h2>📋 Cari Hesaplar</h2>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-new" id="btnImport" style="background: #28a745;">📥 Excel İçe Aktar</button>
                    <a href="{{ route('cari.create') }}" class="btn-new">➕ Yeni Cari Ekle</a>
                </div>
            </div>

            <!-- Arama Kutusu -->
            <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <div style="flex: 1; position: relative;">
                        <input type="text" id="searchInput" placeholder="🔍 Cari ara (ad, kod, telefon, email, il...)" style="width: 100%; padding: 12px 15px 12px 40px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 14px; transition: all 0.3s;">
                        <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); font-size: 18px;">🔍</span>
                    </div>
                    <button type="button" id="btnClearSearch" style="padding: 12px 20px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer; display: none;">✕ Temizle</button>
                </div>
                <div id="searchInfo" style="margin-top: 10px; color: #666; font-size: 13px; display: none;"></div>
            </div>

            <div class="table-container">
                <div class="table-responsive" id="cariTableContainer">
                    @include('cari.partials.table', ['cariHesaplar' => $cariHesaplar])
                </div>
                <div id="paginationContainer">
                    {{ $cariHesaplar->links('cari.partials.pagination') }}
                </div>
            </div>
        </main>
    </div>

    <!-- İçe Aktar Modal -->
    <div class="modal" id="importModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div class="modal-content" style="background: white; border-radius: 15px; padding: 30px; max-width: 600px; width: 90%;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #333;">📥 Excel Dosyasından İçe Aktar</h3>
                <button class="btn-close" id="btnCloseModal" style="background: none; border: none; font-size: 24px; cursor: pointer;">✕</button>
            </div>
            <div style="background: #e3f2fd; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <strong>Excel Formatı:</strong><br>
                <small>Müşteri Kodu | Grup | Müşteri Ünvanı | E-Posta | Sabit Telefon | GSM | Adres | İl | İlçe | Vergi Dairesi | Vergi Numarası | IBAN | Bakiye | Kayıt Tarihi</small>
            </div>
            <form id="importForm" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #333; font-weight: 500;">Dosya Seçin (.xlsx, .xls, .csv)</label>
                    <input type="file" name="file" id="importFile" accept=".xlsx,.xls,.csv" required style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 8px;">
                </div>
                <div class="alert alert-error" id="importError" style="display: none; padding: 12px; background: #fee; color: #c33; border-radius: 8px; margin-bottom: 15px;"></div>
                <div class="alert alert-success" id="importSuccess" style="display: none; padding: 12px; background: #efe; color: #3c3; border-radius: 8px; margin-bottom: 15px;"></div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="btnCancelImport">İptal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitImport">📥 İçe Aktar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // CSRF token ayarla
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Mobil menü toggle
            $('#menuToggle').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#sidebarOverlay').toggleClass('active');
            });

            // Overlay'e tıklayınca menüyü kapat
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

            // İçe aktar modal aç
            $('#btnImport').on('click', function() {
                $('#importModal').css('display', 'flex');
            });

            // Modal kapat
            $('#btnCloseModal, #btnCancelImport').on('click', function() {
                $('#importModal').hide();
                $('#importForm')[0].reset();
                $('#importError').hide();
                $('#importSuccess').hide();
            });

            // Modal dışına tıklayınca kapat
            $('#importModal').on('click', function(e) {
                if (e.target === this) {
                    $(this).hide();
                }
            });

            // İçe aktar form submit
            $('#importForm').on('submit', function(e) {
                e.preventDefault();

                $('#importError').hide();
                $('#importSuccess').hide();

                const $btn = $('#btnSubmitImport');
                const originalText = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner"></span> Yükleniyor...');

                const formData = new FormData(this);

                $.ajax({
                    url: '{{ route('cari.import') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $btn.prop('disabled', false).html(originalText);

                        if (response.success) {
                            $('#importSuccess').text(response.message).fadeIn();
                            
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalText);
                        const message = xhr.responseJSON?.message || 'Dosya yüklenirken bir hata oluştu!';
                        $('#importError').text(message).fadeIn();
                    }
                });
            });

            // Arama işlevi
            let searchTimeout;
            function performSearch(page = 1) {
                const search = $('#searchInput').val();
                
                $.ajax({
                    url: '{{ route('cari.index') }}',
                    method: 'GET',
                    data: { 
                        search: search,
                        page: page
                    },
                    success: function(response) {
                        $('#cariTableContainer').html(response.html);
                        $('#paginationContainer').html(response.pagination);
                        
                        if (search) {
                            $('#btnClearSearch').show();
                            $('#searchInfo').text('Arama sonuçları gösteriliyor').show();
                        } else {
                            $('#btnClearSearch').hide();
                            $('#searchInfo').hide();
                        }
                    },
                    error: function() {
                        alert('Arama sırasında bir hata oluştu!');
                    }
                });
            }

            // Arama inputu
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    performSearch();
                }, 500); // 500ms bekle
            });

            // Arama temizle
            $('#btnClearSearch').on('click', function() {
                $('#searchInput').val('');
                performSearch();
            });

            // Pagination linkleri
            $(document).on('click', '.pagination-link', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                const page = new URL(url).searchParams.get('page');
                performSearch(page);
                
                // Scroll to top
                $('html, body').animate({ scrollTop: 0 }, 300);
            });
        });
    </script>
</body>
</html>

