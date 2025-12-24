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
        Schema::create('teklif_urunler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teklif_id');
            $table->string('sku')->nullable();
            $table->string('stok_tanimi');
            $table->decimal('miktar', 10, 2)->default(1);
            $table->string('birim')->default('ADET');
            $table->decimal('birim_fiyat', 12, 2)->default(0);
            $table->decimal('indirim_oran', 5, 2)->default(0);
            $table->decimal('indirim_tutar', 12, 2)->default(0);
            $table->decimal('kdv_oran', 5, 2)->default(0);
            $table->decimal('kdv_tutar', 12, 2)->default(0);
            $table->decimal('tevkifat', 5, 2)->default(0);
            $table->decimal('tutar_kdv_haric', 12, 2)->default(0);
            $table->decimal('tutar_kdv_dahil', 12, 2)->default(0);
            $table->string('depo')->nullable();
            $table->integer('sira_no')->default(0);
            $table->timestamps();
            
            $table->foreign('teklif_id')->references('id')->on('teklifler')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teklif_urunler');
    }
};
