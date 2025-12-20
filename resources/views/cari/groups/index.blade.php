<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cari Grupları - Servis Takip</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .groups-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .group-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            position: relative;
        }

        .group-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .group-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .group-icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .group-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .group-description {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .group-count {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-delete-group {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .btn-delete-group:hover {
            background: #c0392b;
        }

        .btn-delete-group:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .add-group-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            color: white;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            transition: all 0.3s;
        }

        .add-group-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
        }

        .add-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .add-text {
            font-size: 16px;
            font-weight: 600;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: #333;
        }

        .btn-close {
            background: none;
            border: none;
            font-size: 24px;
            color: #999;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-close:hover {
            color: #333;
        }

        @media (max-width: 768px) {
            .groups-grid {
                grid-template-columns: 1fr;
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
                <h2>📁 Cari Grupları</h2>
            </div>

            <div class="alert alert-success" id="alertSuccess" style="display: none;"></div>
            <div class="alert alert-error" id="alertError" style="display: none;"></div>

            <div class="groups-grid">
                <!-- Yeni Grup Ekle Kartı -->
                <div class="add-group-card" id="btnAddGroup">
                    <div class="add-icon">➕</div>
                    <div class="add-text">Yeni Grup Ekle</div>
                </div>

                <!-- Mevcut Gruplar -->
                @foreach($cariGroups as $group)
                <div class="group-card" data-id="{{ $group->id }}">
                    <div class="group-header">
                        <div class="group-icon">📁</div>
                        <button class="btn-delete-group" data-id="{{ $group->id }}" 
                                {{ $group->cari_hesaplar_count > 0 ? 'disabled' : '' }}
                                title="{{ $group->cari_hesaplar_count > 0 ? 'Bu gruba ait cari hesaplar var' : 'Grubu sil' }}">
                            🗑️
                        </button>
                    </div>
                    <div class="group-name">{{ $group->name }}</div>
                    <div class="group-description">{{ $group->description ?? 'Açıklama yok' }}</div>
                    <div class="group-count">
                        👥 {{ $group->cari_hesaplar_count }} Cari
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </div>

    <!-- Yeni Grup Modal -->
    <div class="modal" id="addGroupModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">➕ Yeni Cari Grubu Ekle</h3>
                <button class="btn-close" id="btnCloseModal">✕</button>
            </div>
            <form id="addGroupForm">
                @csrf
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="name" class="required">Grup Adı</label>
                    <input type="text" id="name" name="name" class="form-control" required autofocus>
                    <span class="error-text" id="error-name"></span>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="description">Açıklama</label>
                    <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" id="btnCancelModal">İptal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitGroup">💾 Kaydet</button>
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

            // Modal aç
            $('#btnAddGroup').on('click', function() {
                $('#addGroupModal').addClass('active');
                $('#name').focus();
            });

            // Modal kapat
            $('#btnCloseModal, #btnCancelModal').on('click', function() {
                $('#addGroupModal').removeClass('active');
                $('#addGroupForm')[0].reset();
                $('.error-text').hide();
            });

            // Modal dışına tıklayınca kapat
            $('#addGroupModal').on('click', function(e) {
                if (e.target === this) {
                    $(this).removeClass('active');
                }
            });

            // Grup ekle
            $('#addGroupForm').on('submit', function(e) {
                e.preventDefault();

                $('.error-text').hide();
                const $btn = $('#btnSubmitGroup');
                const originalText = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner"></span> Kaydediliyor...');

                $.ajax({
                    url: '{{ route('cari.groups.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#alertSuccess').text(response.message).fadeIn();
                            $('#addGroupModal').removeClass('active');
                            $('#addGroupForm')[0].reset();
                            
                            // Sayfayı yenile
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalText);
                        
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $(`#error-${field}`).text(errors[field][0]).show();
                            }
                        } else {
                            $('#alertError').text('Bir hata oluştu!').fadeIn();
                        }
                    }
                });
            });

            // Grup sil
            $('.btn-delete-group').on('click', function() {
                if ($(this).is(':disabled')) return;
                
                if (!confirm('Bu cari grubunu silmek istediğinizden emin misiniz?')) {
                    return;
                }

                const groupId = $(this).data('id');
                const $card = $(`.group-card[data-id="${groupId}"]`);

                $.ajax({
                    url: `/cari-groups/${groupId}`,
                    method: 'DELETE',
                    success: function(response) {
                        if (response.success) {
                            $('#alertSuccess').text(response.message).fadeIn();
                            $card.fadeOut(300, function() {
                                $(this).remove();
                            });
                            
                            setTimeout(function() {
                                $('#alertSuccess').fadeOut();
                            }, 3000);
                        }
                    },
                    error: function(xhr) {
                        const message = xhr.responseJSON?.message || 'Bir hata oluştu!';
                        $('#alertError').text(message).fadeIn();
                        
                        setTimeout(function() {
                            $('#alertError').fadeOut();
                        }, 5000);
                    }
                });
            });

            // Input focus olunca hataları temizle
            $('.form-control').on('focus', function() {
                const fieldName = $(this).attr('name');
                $(`#error-${fieldName}`).hide();
            });
        });
    </script>
</body>
</html>

