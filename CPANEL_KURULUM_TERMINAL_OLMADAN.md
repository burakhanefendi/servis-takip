# 🚀 cPanel Kurulumu (Terminal Olmadan)

## Terminal erişiminiz yoksa bu rehberi takip edin!

---

## ✅ KURULUM ÖZETİ

1. ✅ **Yerel hazırlık** (10 dakika)
2. ✅ **cPanel veritabanı** (5 dakika)
3. ✅ **Dosya yükleme** (10 dakika)
4. ✅ **İzin ayarları** (5 dakika)
5. ✅ **Domain ayarları** (5 dakika)
6. ✅ **Web üzerinden tenant oluşturma** (2 dakika)

**Toplam: ~40 dakika**

---

## 📋 1. YEREL BİLGİSAYARDA HAZIRLIK

### A) Vendor Klasörünü Oluşturun

```bash
# Proje klasöründe
cd C:\laragon\www\servis-takip

# Composer bağımlılıkları
composer install --no-dev --optimize-autoloader
```

**✅ Şimdi `vendor` klasörü var**

---

### B) .env.production Dosyası

Proje kök dizininde `.env.production` dosyası oluşturun:

```env
APP_NAME="Arıtma Takip"
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=https://aritmapp.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanel_kullanici_aritmapp_main
DB_USERNAME=cpanel_kullanici_aritmapp_user
DB_PASSWORD=BURAYA_SIFRE_GELECEK

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

---

### C) APP_KEY Oluşturun

**Yöntem 1: Online Generator**
- https://generate-random.org/laravel-key-generator
- Oluşan key'i kopyalayın
- `.env.production` dosyasında `APP_KEY=` kısmına yapıştırın

**Yöntem 2: Yerel Terminal**
```bash
php artisan key:generate --show
```
Çıkan key'i `.env.production` dosyasına yapıştırın.

**Örnek:**
```env
APP_KEY=base64:abcdef1234567890...
```

---

### D) Cache Temizliği

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Gereksiz dosyaları temizle
rmdir /s /q storage\framework\cache\data
rmdir /s /q storage\framework\sessions
rmdir /s /q storage\framework\views
del /q storage\logs\*.log
```

---

### E) Projeyi ZIP'leyin

1. **Proje klasörüne sağ tık**
2. **"Send to" → "Compressed (zipped) folder"**
3. Dosya adı: **`servis-takip.zip`**

⚠️ **ÖNEMLİ:** `vendor` klasörü ZIP içinde olmalı!

**Dosya boyutu:** ~100-150 MB olacaktır.

---

## 🌐 2. cPANEL VERİTABANI

### A) cPanel'e Giriş

```
https://aritmapp.com:2083
VEYA
https://cpanel.aritmapp.com
```

Login yapın.

---

### B) MySQL Database Wizard

1. **Databases** → **MySQL Database Wizard**

2. **Adım 1: Veritabanı Oluştur**
   ```
   Database Name: aritmapp_main
   ```
   → **Next Step**

3. **Adım 2: Kullanıcı Oluştur**
   ```
   Username: aritmapp_user
   Password: [Güçlü şifre - not alın!]
   Password Strength: 100/100
   ```
   → **Create User**

4. **Adım 3: Yetkilendirme**
   ```
   ✅ ALL PRIVILEGES (Tümü seçili)
   ```
   → **Make Changes**

---

### C) Bilgileri Not Edin

cPanel otomatik olarak prefix ekler:

```
Database: cpanel_kullanici_aritmapp_main
User: cpanel_kullanici_aritmapp_user
Password: [sizin seçtiğiniz]
Host: localhost
```

**Bu bilgileri .env dosyasında kullanacaksınız!**

---

## 📤 3. DOSYA YÜKLEME

### A) File Manager Aç

cPanel → **Files** → **File Manager**

---

### B) Klasör Oluştur

1. `public_html` klasörüne gidin
2. **+ Folder** tıklayın
3. İsim: **`laravel-app`**
4. **Create New Folder**

---

### C) ZIP Yükleme

1. `laravel-app` klasörüne girin
2. **Upload** butonuna tıklayın
3. `servis-takip.zip` dosyasını seçin
4. Yükleme tamamlanınca geri dönün

⏳ **Bekleme süresi:** ~5-10 dakika (dosya boyutuna göre)

---

### D) ZIP'i Çıkar

1. `servis-takip.zip` dosyasına **sağ tık**
2. **Extract** seçin
3. Path kontrolü:
   ```
   /home/cpanel_kullanici/public_html/laravel-app
   ```
4. **Extract File(s)** tıklayın

---

### E) .env Dosyasını Düzenle

1. `.env.production` dosyasına **sağ tık** → **Rename**
2. Yeni adı: **`.env`**

3. `.env` dosyasını **Edit** edin

4. Veritabanı bilgilerini güncelleyin:
   ```env
   DB_DATABASE=cpanel_kullanici_aritmapp_main
   DB_USERNAME=cpanel_kullanici_aritmapp_user
   DB_PASSWORD=veritagbani_sifreniz
   ```

5. **Save Changes**

---

## 🔒 4. İZİN AYARLARI

### A) Storage Klasörü

1. File Manager'da `storage` klasörüne **sağ tık**
2. **Change Permissions**
3. Ayarlar:
   ```
   Owner: ✅ Read  ✅ Write  ✅ Execute
   Group: ✅ Read  ✅ Write  ✅ Execute
   World: ✅ Read  ⬜ Write  ✅ Execute
   
   Numeric: 775
   ```
4. **✅ Recurse into subdirectories** işaretleyin
5. **Change Permissions**

---

### B) bootstrap/cache Klasörü

Aynı işlemi `bootstrap/cache` klasörü için tekrarlayın.

---

### C) Storage Link (Symlink)

1. `public` klasörüne gidin
2. **+ Symbolic Link** tıklayın
3. Ayarlar:
   ```
   Link Path: /home/cpanel_kullanici/public_html/laravel-app/public/storage
   Target: /home/cpanel_kullanici/public_html/laravel-app/storage/app/public
   ```
4. **Create Symbolic Link**

⚠️ **Path'leri kendi kullanıcı adınıza göre değiştirin!**

---

## 🏠 5. DOMAIN AYARLARI

### A) Ana Domain Document Root

1. cPanel → **Domains**
2. **aritmapp.com** yanındaki **⚙️ Manage**
3. **Document Root** değiştirin:
   ```
   /home/cpanel_kullanici/public_html/laravel-app/public
   ```
4. **Submit**

---

### B) public_html/.htaccess

1. `public_html` klasörüne gidin
2. **+ File** → İsim: **`.htaccess`**
3. Dosyayı **Edit** edin:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ laravel-app/public/$1 [L]
</IfModule>
```

4. **Save Changes**

---

## 📊 6. VERİTABANI TABLOLARI

### A) phpMyAdmin Aç

cPanel → **Databases** → **phpMyAdmin**

---

### B) Veritabanını Seç

Sol menüden: **cpanel_kullanici_aritmapp_main**

---

### C) SQL Sekmesi

**SQL** sekmesine tıklayın.

---

### D) Tenants Tablosu

Aşağıdaki SQL'i çalıştırın:

```sql
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subdomain` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `database_name` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `subscription_expires` date DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_subdomain_unique` (`subdomain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Go** tıklayın.

---

### E) Diğer Tabloları Import Edin

1. **Import** sekmesine gidin
2. Yerel bilgisayardan `database.sql` yükleyin (varsa)

**VEYA**

Siteye giriş yapınca otomatik oluşturulmasını bekleyin.

---

## 🌐 7. TEST

### Site Kontrolü

```
https://aritmapp.com
```

✅ Giriş sayfası veya dashboard açılmalı

---

## 🎯 8. WEB ÜZERİNDEN TENANT OLUŞTURMA

### A) Tenant Yönetim Sayfası

```
https://aritmapp.com/admin/tenants
```

### B) Yeni Tenant Oluştur

1. **"Yeni Tenant"** butonuna tıklayın
2. **Form Bilgileri:**
   ```
   Subdomain: kapakli
   Firma Adı: Kapaklı Su Arıtma
   Abonelik Bitiş: (opsiyonel)
   ```
3. **Tenant Oluştur** tıklayın

✅ **Başarılı!** Veritabanı ve kayıt oluşturuldu.

---

### C) cPanel'den Subdomain Ekle

1. cPanel → **Domains** → **Create A New Domain**
2. Ayarlar:
   ```
   Domain: kapakli.aritmapp.com
   
   ✅ Share document root with "aritmapp.com"
   ```
   **VEYA**
   ```
   Document Root: /home/cpanel_kullanici/public_html/laravel-app/public
   ```
3. **Submit**

---

### D) Test Et

```
https://kapakli.aritmapp.com
```

✅ Login sayfası açılmalı!

---

## 🔒 9. SSL SERTİFİKASI

### AutoSSL

1. cPanel → **Security** → **SSL/TLS Status**
2. Domain'leri seçin:
   ```
   ✅ aritmapp.com
   ✅ www.aritmapp.com
   ✅ kapakli.aritmapp.com
   ```
3. **Run AutoSSL** tıklayın

⏳ **5-10 dakika içinde SSL hazır!**

---

## ✅ KURULUM TAMAMLANDI!

### Başarı Kontrol Listesi

- ✅ Laravel dosyaları yüklendi
- ✅ .env dosyası yapılandırıldı
- ✅ Veritabanı oluşturuldu
- ✅ Storage izinleri ayarlandı
- ✅ Ana domain çalışıyor
- ✅ Web panel tenant oluşturuyor
- ✅ Subdomain eklendi
- ✅ SSL aktif

---

## 🎊 YENİ MÜŞTERİ EKLEME (2 DAKİKA!)

### Adım 1: Web Panelden Oluştur

```
https://aritmapp.com/admin/tenants → Yeni Tenant
```

### Adım 2: cPanel'den Subdomain Ekle

```
Domain: yeni-musteri.aritmapp.com
Document Root: .../laravel-app/public
```

### Adım 3: Müşteriye Ver

```
https://yeni-musteri.aritmapp.com
```

**Bitti! ✅**

---

## 🆘 SORUN GİDERME

### 500 Hatası

1. Storage izinlerini kontrol edin (775)
2. .env dosyasını kontrol edin
3. `error_log` dosyalarına bakın

---

### Veritabanı Hatası

1. .env'deki bilgileri kontrol edin
2. phpMyAdmin'den bağlantıyı test edin
3. Kullanıcı yetkilerini kontrol edin

---

### Subdomain Çalışmıyor

1. DNS yayılmasını bekleyin (5-15 dakika)
2. Document Root'u kontrol edin
3. SSL sertifikasını kontrol edin

---

## 🎉 BAŞARILAR!

Artık terminal olmadan cPanel'de multi-tenant Laravel sisteminiz çalışıyor!

**Sorularınız için:** Laravel log dosyalarını kontrol edin
- `storage/logs/laravel.log`
- cPanel Error Logs

---

## 📱 HIZLI YARDIM

### Cron Job ile Artisan (Alternatif)

Terminal yoksa artisan komutlarını cron job ile çalıştırabilirsiniz:

cPanel → **Advanced** → **Cron Jobs**

```bash
# Storage link
cd /home/kullanici/public_html/laravel-app && /usr/local/bin/php artisan storage:link

# Cache temizleme
cd /home/kullanici/public_html/laravel-app && /usr/local/bin/php artisan cache:clear
```

**1 dakika bekleyin, sonra cron job'ı silin.**

---

**İyi çalışmalar! 🚀**

