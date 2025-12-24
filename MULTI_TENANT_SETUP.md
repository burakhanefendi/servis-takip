# 🏢 Multi-Tenant Kurulum Rehberi

## ✅ Kurulum Tamamlandı!

Multi-tenant yapısı başarıyla projenize entegre edildi. Artık her müşteri için ayrı subdomain ve veritabanı oluşturabilirsiniz.

---

## 📋 Yapılan Değişiklikler

### 1. Yeni Dosyalar
- ✅ `app/Models/Tenant.php` - Tenant modeli
- ✅ `app/Http/Middleware/TenantMiddleware.php` - Subdomain algılama
- ✅ `app/Console/Commands/CreateTenant.php` - Tenant oluşturma komutu
- ✅ `app/Console/Commands/ListTenants.php` - Tenant listeleme komutu
- ✅ `database/migrations/*_create_tenants_table.php` - Tenants tablosu
- ✅ `resources/views/errors/tenant-inactive.blade.php` - Pasif hesap sayfası

### 2. Güncellenen Dosyalar
- ✅ `bootstrap/app.php` - TenantMiddleware eklendi

---

## 🚀 Kullanım

### Yeni Müşteri (Tenant) Oluşturma

```bash
php artisan tenant:create <subdomain> "<Firma Adı>" [--expires=YYYY-MM-DD]
```

**Örnek:**
```bash
php artisan tenant:create kapakli "Kapaklı Su Arıtma"
php artisan tenant:create bodrum "Bodrum Su Arıtma" --expires=2026-12-31
php artisan tenant:create cesme "Çeşme Su Arıtma"
```

Bu komut:
1. ✅ Yeni veritabanı oluşturur (`aritmapp_<subdomain>`)
2. ✅ Tenant kaydı ekler
3. ✅ Migration'ları çalıştırır
4. ✅ Sistem hazır hale gelir

### Tenant Listesi

```bash
php artisan tenant:list
```

Tüm tenant'ları tablo halinde gösterir.

---

## 🌐 cPanel Kurulumu

Her yeni tenant için cPanel'den subdomain oluşturmanız gerekiyor:

### Adımlar:

1. **cPanel'e Giriş Yapın**
   - https://aritmapp.com:2083

2. **Domains → Create A New Domain**

3. **Ayarlar:**
   ```
   Domain: kapakli.aritmapp.com
   Document Root: /home/kullanici/laravel-app/public
   ✅ Share document root (işaretli)
   ```

4. **SSL Sertifikası**
   - cPanel → SSL/TLS Status
   - AutoSSL ile otomatik sertifika al

5. **Test Et**
   ```
   https://kapakli.aritmapp.com
   ```

---

## 📊 Veritabanı Yapısı

```
Ana Veritabanı (aritmapp_main):
├── tenants (Tüm müşteri kayıtları)
├── users (Admin kullanıcıları - opsiyonel)
└── ...

Müşteri Veritabanları:
├── aritmapp_kapakli
│   ├── cari_hesaplar
│   ├── servisler
│   ├── teklifler
│   └── ...
│
├── aritmapp_bodrum
│   ├── cari_hesaplar
│   ├── servisler
│   └── ...
│
└── aritmapp_cesme
    └── ...
```

**Her müşterinin verisi tamamen ayrı veritabanında!**

---

## 🔧 Nasıl Çalışır?

### 1. Kullanıcı Erişimi

```
https://kapakli.aritmapp.com
         ↓
TenantMiddleware subdomain'i yakalar
         ↓
Tenant kaydını bulur (tenants tablosu)
         ↓
aritmapp_kapakli veritabanına bağlanır
         ↓
Kullanıcı sadece kendi verisini görür
```

### 2. Veri İzolasyonu

```php
// Kapaklı müşterisi login olduğunda
Session: tenant_id = 1
Database: aritmapp_kapakli

// Bodrum müşterisi login olduğunda
Session: tenant_id = 2
Database: aritmapp_bodrum
```

**Veriler fiziksel olarak ayrı, karışma riski YOK!**

---

## 🛡️ Güvenlik

### Otomatik Kontroller:
- ✅ Her istek subdomain'e göre doğru DB'ye yönlenir
- ✅ Pasif tenant'lara erişim engellenir
- ✅ Süresi dolmuş hesaplar otomatik bloke edilir
- ✅ Veritabanı izolasyonu sayesinde veri karışmaz

### Manuel Güvenlik:
```php
// View'larda tenant bilgisine erişim
{{ $currentTenant->name }}

// Controller'da
$tenantId = session('tenant_id');
$tenantName = session('tenant_name');
```

---

## 🔄 Güncelleme ve Bakım

### Kod Güncellemesi (Tüm Tenant'lar İçin)

```bash
# Local'de değişiklik yap
git add .
git commit -m "Yeni özellik eklendi"
git push

# Sunucuda
cd /home/kullanici/laravel-app
git pull
composer install --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Tamamlandı! Tüm tenant'lar güncellendi ✅
```

### Migration Ekleme

```bash
# Local'de migration oluştur
php artisan make:migration add_new_column_to_servisler

# Sunucuda - TÜM tenant DB'lerine uygula
php artisan tenant:migrate-all  # Bu komutu ekleyeceğiz
```

---

## 📦 Sunucuya Canlı Alma

### 1. Dosyaları Yükle

```bash
# Git ile (önerilen)
cd /home/kullanici
git clone https://github.com/kullanici/servis-takip.git laravel-app
cd laravel-app
```

### 2. Composer ve Ayarlar

```bash
composer install --no-dev --optimize-autoloader
cp .env.example .env
nano .env
```

**.env Ayarları:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://aritmapp.com

# Ana veritabanı
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aritmapp_main
DB_USERNAME=kullanici
DB_PASSWORD=sifre
```

### 3. Kurulum

```bash
php artisan key:generate
php artisan migrate --force
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

### 4. İlk Tenant'ı Oluştur

```bash
php artisan tenant:create kapakli "Kapaklı Su Arıtma"
```

### 5. cPanel'den Subdomain Ekle

```
kapakli.aritmapp.com → /home/kullanici/laravel-app/public
```

### 6. Test Et! 🎉

```
https://kapakli.aritmapp.com
```

---

## 🎯 Örnek Senaryolar

### Senaryo 1: 5 Yeni Müşteri Eklemek

```bash
php artisan tenant:create kapakli "Kapaklı Su Arıtma"
php artisan tenant:create bodrum "Bodrum Su Arıtma"  
php artisan tenant:create cesme "Çeşme Su Arıtma"
php artisan tenant:create kusadasi "Kuşadası Su Arıtma"
php artisan tenant:create izmir "İzmir Su Arıtma"

# Her biri için cPanel'den subdomain ekle (5 × 30 saniye = 2.5 dakika)
```

⏱️ **Toplam Süre: ~5 dakika**

### Senaryo 2: Tüm Müşterilere Yeni Özellik Eklemek

```bash
# Kod değişikliği yap
git pull
php artisan config:cache

# Tamamlandı! ✅ (Tek seferlik işlem)
```

⏱️ **Toplam Süre: 2 dakika**

### Senaryo 3: Bir Müşteriyi Pasif Hale Getirmek

```bash
php artisan tinker
```

```php
$tenant = Tenant::where('subdomain', 'kapakli')->first();
$tenant->active = false;
$tenant->save();
```

Müşteri artık sistemegirişyapamaz. "Hesap Askıda" sayfası görür.

---

## 💡 İpuçları

### Veritabanı Yedekleme

```bash
# Tüm tenant DB'lerini yedekle
for db in $(mysql -e "SHOW DATABASES LIKE 'aritmapp_%'" -sN); do
    mysqldump $db > backup_${db}_$(date +%Y%m%d).sql
done
```

### Log İzleme

```bash
# Ana log
tail -f storage/logs/laravel.log

# Tenant bazlı log istiyorsanız özel yapılandırma gerekir
```

### Performans

```bash
# Cache'leri optimize et
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer dump-autoload --optimize
```

---

## ❓ Sık Sorulan Sorular

### S: Her tenant için ayrı ayarlar dosyası olabilir mi?

**C:** Evet! `settings` tablosu her tenant'ın kendi DB'sinde. Her müşteri kendi logosunu, firma bilgilerini ayarlayabilir.

### S: Bir müşterinin verisi diğerine karışabilir mi?

**C:** Hayır! Fiziksel olarak ayrı veritabanları kullanıldığı için imkansız.

### S: 100 müşterim olduğunda sorun olur mu?

**C:** Hayır! Multi-tenant yapısı binlerce müşteriyi destekler. Tek kod tabanı, ayrı veriler.

### S: Müşteri kendi subdomain'ini değiştirebilir mi?

**C:** Hayır. Subdomain sadece sizin tarafınızdan değiştirilebilir.

---

## 📞 Destek

Herhangi bir sorun yaşarsanız:
1. `storage/logs/laravel.log` dosyasını kontrol edin
2. `php artisan tenant:list` ile tenant'ları kontrol edin
3. cPanel subdomain ayarlarını kontrol edin

---

## 🎉 Başarılar!

Multi-tenant sisteminiz hazır! Artık her müşteri için ayrı subdomain ve veritabanı ile güvenli, ölçeklenebilir bir SaaS platformunuz var.

**İyi çalışmalar! 🚀**

