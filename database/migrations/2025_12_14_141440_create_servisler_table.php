<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servisler', function (Blueprint $table) {
            $table->id();
            $table->string('servis_no')->unique(); // Otomatik servis numarası
            
            // Cari Hesap Bilgileri (Step 1)
            $table->foreignId('cari_hesap_id')->constrained('cari_hesaplar')->onDelete('cascade');
            $table->string('eposta')->nullable();
            $table->string('gsm')->nullable();
            $table->string('sabit_telefon')->nullable();
            $table->string('il')->nullable();
            $table->string('ilce')->nullable();
            $table->text('adres')->nullable();
            
            // Ürün Bilgileri (Step 2)
            $table->string('marka')->nullable();
            $table->string('model')->nullable();
            $table->string('seri_numarasi')->nullable();
            $table->string('urun_cinsi')->nullable();
            $table->string('model_kodu')->nullable();
            $table->string('urun_rengi')->nullable();
            $table->string('garanti_durumu')->nullable();
            $table->string('fatura_numarasi')->nullable();
            $table->date('fatura_tarihi')->nullable();
            
            // Detaylar (Step 3)
            $table->text('urunun_fiziksel_durumu')->nullable();
            $table->string('oncelik_durumu')->nullable(); // Normal, Acil, Çok Acil
            $table->text('musterinin_sikayeti')->nullable();
            $table->string('ariza_tanimi')->nullable(); // Filtre Değişimi, Genel Bakım, Montaj ve İlk Kurulum
            $table->text('teknisyenin_yorumu')->nullable();
            
            // Ücret ve Bilgilendirme (Step 4)
            $table->decimal('tahmini_ucret', 10, 2)->nullable();
            $table->string('teslimat_turu')->default('Elden'); // Elden, Kargo şirketleri
            $table->string('kargo_sirket')->nullable();
            $table->date('tahmini_bitis_tarihi')->nullable();
            $table->string('personel')->nullable();
            $table->dateTime('randevu_tarihi')->nullable();
            
            // Durum
            $table->string('durum')->default('Beklemede'); // Beklemede, İşlemde, Tamamlandı, İptal
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servisler');
    }
};
