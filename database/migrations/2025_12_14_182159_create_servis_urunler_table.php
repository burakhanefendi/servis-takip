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
        Schema::create('servis_urunler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servisler')->onDelete('cascade');
            $table->string('stok_adi')->nullable(); // Ürün/Hizmet adı
            $table->string('stok_kodu')->nullable(); // Stok kodu
            $table->decimal('miktar', 10, 2)->default(1); // Miktar
            $table->string('birim')->default('ADET'); // ADET, KG, LT, etc.
            $table->string('para_birimi')->default('₺ Türk Lirası'); // Para birimi
            $table->decimal('birim_fiyat', 10, 2)->default(0); // Birim fiyat
            $table->decimal('kdv_orani', 5, 2)->default(20); // KDV oranı %
            $table->decimal('kdv_tutari', 10, 2)->default(0); // KDV tutarı
            $table->decimal('toplam_kdv_haric', 10, 2)->default(0); // KDV hariç toplam
            $table->decimal('toplam_kdv_dahil', 10, 2)->default(0); // KDV dahil toplam
            $table->string('depo')->default('Varsayılan'); // Depo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servis_urunler');
    }
};
