<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Servis Takip - Giriş Yap</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <div class="login-icon">💧</div>
            <h1>Servis Takip Sistemi</h1>
            <p>Su Arıtma Müşteri Yönetimi</p>
        </div>

        <div class="login-body">
            <div class="alert alert-error" id="alertBox"></div>

            <form id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="email">E-posta Adresi</label>
                    <input type="email" id="email" name="email" placeholder="ornek@email.com" required autofocus>
                    <span class="error-text" id="emailError"></span>
                </div>

                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                    <span class="error-text" id="passwordError"></span>
                </div>

                <div class="remember-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Beni Hatırla</label>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    Giriş Yap
                </button>
            </form>
        </div>

        <div class="login-footer">
            © 2025 Servis Takip Sistemi. Tüm hakları saklıdır.
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

            // Form submit
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                // Hata mesajlarını temizle
                $('.error-text').hide();
                $('#alertBox').hide();

                // Button'u disable et
                const $btn = $('#loginBtn');
                const originalText = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner"></span> Giriş yapılıyor...');

                // AJAX request
                $.ajax({
                    url: '{{ route('login') }}',
                    method: 'POST',
                    data: {
                        email: $('#email').val(),
                        password: $('#password').val(),
                        remember: $('#remember').is(':checked') ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#alertBox')
                                .removeClass('alert-error')
                                .addClass('alert-success')
                                .text(response.message)
                                .fadeIn();
                            
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html(originalText);

                        if (xhr.status === 422) {
                            // Validation hataları
                            const errors = xhr.responseJSON.errors;
                            if (errors.email) {
                                $('#emailError').text(errors.email[0]).show();
                            }
                            if (errors.password) {
                                $('#passwordError').text(errors.password[0]).show();
                            }
                        } else if (xhr.status === 401) {
                            // Login başarısız
                            $('#alertBox')
                                .removeClass('alert-success')
                                .addClass('alert-error')
                                .text(xhr.responseJSON.message)
                                .fadeIn();
                        } else {
                            // Diğer hatalar
                            $('#alertBox')
                                .removeClass('alert-success')
                                .addClass('alert-error')
                                .text('Bir hata oluştu. Lütfen tekrar deneyin.')
                                .fadeIn();
                        }
                    }
                });
            });

            // Input focus olunca hataları temizle
            $('input').on('focus', function() {
                $(this).siblings('.error-text').hide();
                $('#alertBox').fadeOut();
            });
        });
    </script>
</body>
</html>

