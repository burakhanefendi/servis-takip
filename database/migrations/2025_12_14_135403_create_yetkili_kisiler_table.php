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
        Schema::create('yetkili_kisiler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cari_hesap_id')->constrained('cari_hesaplar')->onDelete('cascade');
            $table->string('ad_soyad');
            $table->string('unvan')->nullable();
            $table->string('telefon')->nullable();
            $table->string('eposta')->nullable();
            $table->string('dahili')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yetkili_kisiler');
    }
};
