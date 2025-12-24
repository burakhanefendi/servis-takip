# 🚀 cPanel'e Basit Kurulum Rehberi

## Normal Laravel Kurulumu (Multi-Tenant YOK)

Bu versiyon basit tek uygulama olarak çalışır. Tüm kullanıcılar aynı veritabanını kullanır.

---

## 📋 KURULUM ADIMLARI

### 1️⃣ Veritabanı Oluştur

cPanel → **MySQL Database Wizard**

```
Database: aritmapp_main
Username: aritmapp_user
Password: [güçlü şifre]
Privileges: ALL
```

---

### 2️⃣ GitHub'dan Dosyaları Çek

**Yöntem A: cPanel Terminal (varsa)**
```bash
cd public_html
git clone https://github.com/burakhanefendi/servis-takip.git laravel-app
```

**Yöntem B: Terminal Yoksa - Cron Job**
```bash
cd /home/cpanel_kullanici/public_html && git clone https://github.com/burakhanefendi/servis-takip.git laravel-app
```

**Yöntem C: ZIP İndirme**
1. GitHub → Code → Download ZIP
2. cPanel File Manager → Upload → Extract

---

### 3️⃣ .env Dosyası

File Manager'da:

1. `.env.example` → Copy → `.env`
2. `.env` dosyasını Edit:

```env
APP_NAME="Servis Takip"
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=https://aritmapp.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=cpanel_kullanici_aritmapp_main
DB_USERNAME=cpanel_kullanici_aritmapp_user
DB_PASSWORD=VERITABANI_SIFRENIZ
```

**APP_KEY için:** https://generate-random.org/laravel-key-generator

---

### 4️⃣ Document Root

cPanel → **Domains** → **aritmapp.com** → **Manage**

```
Document Root: /home/cpanel_kullanici/public_html/laravel-app/public
```

**ÖNEMLİ:** Sonunda `/public` olmalı!

---

### 5️⃣ İzinler

File Manager'da:

**storage klasörü:**
- Sağ tık → Change Permissions → **775**
- ✅ Recurse into subdirectories

**bootstrap/cache klasörü:**
- Sağ tık → Change Permissions → **775**
- ✅ Recurse into subdirectories

---

### 6️⃣ Storage Symlink

`public` klasöründe **Symbolic Link** oluşturun:

```
Link Path: /home/cpanel_kullanici/public_html/laravel-app/public/storage
Target: /home/cpanel_kullanici/public_html/laravel-app/storage/app/public
```

---

### 7️⃣ Veritabanı Tablolarını Oluştur

**Yöntem A: cPanel Terminal (varsa)**
```bash
cd public_html/laravel-app
php artisan migrate --force
```

**Yöntem B: phpMyAdmin**

cPanel → phpMyAdmin → SQL sekmesi

Migration SQL dosyalarını manuel çalıştırın.

---

### 8️⃣ SSL Sertifikası

cPanel → **SSL/TLS Status**

```
✅ aritmapp.com
✅ www.aritmapp.com
```

**Run AutoSSL**

---

## 🎯 TEST

```
https://aritmapp.com
```

✅ Login/Dashboard sayfası görünmeli!

---

## 🔄 GÜNCELLEME

Yerel bilgisayarda değişiklik yaptıktan sonra:

```bash
# Local
git add .
git commit -m "Güncelleme"
git push origin master

# cPanel (Terminal veya Cron Job)
cd /home/cpanel_kullanici/public_html/laravel-app
git pull origin master
```

---

## 📊 YAPILAN DEĞİŞİKLİKLER

### ✅ AKTİF:
- Normal Laravel uygulaması
- Tek veritabanı
- Tek domain (aritmapp.com)
- Tüm müşteriler aynı sistemde

### ❌ DEVRE DIŞI:
- Multi-tenant yapısı
- Subdomain sistemi
- Ayrı veritabanları
- TenantMiddleware

---

## 💡 SONRADAN MULTI-TENANT EKLEMEKSenaryosu

İleride multi-tenant eklemek isterseniz:

1. `bootstrap/app.php` → TenantMiddleware yorumunu kaldır
2. `php artisan migrate` → tenants tablosunu oluştur
3. Her müşteri için subdomain + tenant kaydı oluştur

**Şimdilik gerek yok!**

---

## 🆘 SORUN GİDERME

### "Index of" Listesi Görünüyor

**Çözüm:** Document Root yanlış
```
Document Root: .../laravel-app/public
```

### 500 Internal Server Error

**Çözüm:** İzinler veya .env
```
storage → 775
bootstrap/cache → 775
APP_KEY → dolu olmalı
```

### Database Connection Error

**Çözüm:** .env veritabanı bilgileri
```
DB_DATABASE, DB_USERNAME, DB_PASSWORD kontrol et
```

---

## ✅ BAŞARILI KURULUM KONTROLÜ

```
✅ https://aritmapp.com → Laravel sayfası açılıyor
✅ Login yapabiliyorum
✅ Müşteri ekleyebiliyorum
✅ Servis oluşturabiliyorum
✅ Teklif oluşturabiliyorum
```

**Hepsi çalışıyorsa kurulum başarılı!** 🎉

---

## 📞 NOTLAR

- Bu basit versiyon, tek domain için
- Tüm kullanıcılar aynı veritabanını kullanır
- Multi-tenant ihtiyacınız olduğunda aktif edilir
- Şimdilik en basit ve hızlı yöntem

**İyi çalışmalar!** 🚀

