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
        Schema::create('teklifler', function (Blueprint $table) {
            $table->id();
            $table->string('teklif_no')->unique();
            $table->unsignedBigInteger('cari_hesap_id');
            $table->string('baslik')->nullable();
            $table->date('baslangic_tarihi');
            $table->date('gecerlilik_tarihi');
            $table->enum('para_birimi', ['TRY', 'USD', 'EUR', 'GBP'])->default('TRY');
            $table->boolean('fotograflar_goster')->default(false);
            $table->decimal('mal_hizmet_tutari', 12, 2)->default(0);
            $table->decimal('toplam_iskonto', 12, 2)->default(0);
            $table->decimal('ara_toplam', 12, 2)->default(0);
            $table->decimal('hesaplanan_kdv', 12, 2)->default(0);
            $table->decimal('genel_toplam', 12, 2)->default(0);
            $table->enum('durum', ['taslak', 'gonderildi', 'onaylandi', 'reddedildi'])->default('taslak');
            $table->text('notlar')->nullable();
            $table->timestamps();
            
            $table->foreign('cari_hesap_id')->references('id')->on('cari_hesaplar')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teklifler');
    }
};
