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
        Schema::table('servisler', function (Blueprint $table) {
            // hatirlatma_zamani alanını datetime'dan string'e çevir
            $table->string('hatirlatma_zamani')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servisler', function (Blueprint $table) {
            // Geri alınırsa datetime'a çevir
            $table->dateTime('hatirlatma_zamani')->nullable()->change();
        });
    }
};
