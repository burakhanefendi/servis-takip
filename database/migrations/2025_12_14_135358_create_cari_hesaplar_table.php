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
        Schema::create('cari_hesaplar', function (Blueprint $table) {
            $table->id();
            
            // Firma Bilgileri
            $table->string('cari_hesap_adi'); // Cari hesap adı (zorunlu)
            $table->string('musteri_kodu')->unique(); // Müşteri kodu (otomatik, unique)
            $table->string('kisa_isim')->nullable(); // Kısa isim
            $table->foreignId('cari_group_id')->nullable()->constrained('cari_groups')->onDelete('set null'); // Cari grubu
            
            // Vergi Bilgileri
            $table->string('vergi_dairesi')->nullable();
            $table->string('vergi_kimlik_no')->nullable(); // Vergi/TC kimlik no
            $table->string('iban')->nullable();
            
            // Adres Bilgileri
            $table->string('il')->nullable();
            $table->string('ilce')->nullable();
            $table->text('adres')->nullable();
            
            // Sevk Adresi
            $table->text('sevk_adresi')->nullable();
            
            // İletişim Bilgileri
            $table->string('gsm')->nullable();
            $table->string('eposta')->nullable();
            $table->string('sabit_telefon')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cari_hesaplar');
    }
};
