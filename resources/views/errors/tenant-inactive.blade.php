<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hesap Askıda</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            max-width: 600px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        
        h1 {
            font-size: 32px;
            color: #2d3748;
            margin-bottom: 15px;
        }
        
        p {
            font-size: 18px;
            color: #718096;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .tenant-name {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 30px;
        }
        
        .info-box {
            background: #f7fafc;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: left;
        }
        
        .info-box strong {
            color: #2d3748;
            display: block;
            margin-bottom: 10px;
        }
        
        .contact {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }
        
        .contact a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">⚠️</div>
        <h1>Hesap Askıda</h1>
        
        <div class="tenant-name">{{ $tenant->name }}</div>
        
        <p>
            Bu hesaba şu anda erişim kapalıdır. 
            Hesabınızın aktif hale gelmesi için lütfen sistem yöneticisi ile iletişime geçin.
        </p>
        
        <div class="info-box">
            <strong>🔍 Olası Sebepler:</strong>
            @if($tenant->subscription_expires && $tenant->subscription_expires->isPast())
            <p style="color: #dc2626; margin: 5px 0;">
                ❌ Abonelik süresi dolmuş ({{ $tenant->subscription_expires->format('d.m.Y') }})
            </p>
            @else
            <p style="color: #9ca3af; margin: 5px 0;">
                • Abonelik ödemesi bekleniyor<br>
                • Hesap geçici olarak devre dışı bırakılmış<br>
                • Sistem bakımı yapılıyor
            </p>
            @endif
        </div>
        
        <div class="contact">
            <p>
                Yardım için:<br>
                <strong>info@aritmapp.com</strong> ile iletişime geçebilirsiniz.
            </p>
        </div>
    </div>
</body>
</html>

