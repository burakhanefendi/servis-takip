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
            $table->dateTime('ilk_bakim_tarihi')->nullable()->after('tamamlanma_tarihi'); // İlk bakım tarihi
            $table->text('bakim_icerigi')->nullable()->after('ilk_bakim_tarihi'); // Bakım içeriği
            $table->text('bakim_lokasyonu')->nullable()->after('bakim_icerigi'); // Bakım lokasyonu (farklı adres varsa)
            $table->dateTime('hatirlatma_zamani')->nullable()->after('bakim_lokasyonu'); // SMS hatırlatma zamanı
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servisler', function (Blueprint $table) {
            $table->dropColumn([
                'ilk_bakim_tarihi',
                'bakim_icerigi',
                'bakim_lokasyonu',
                'hatirlatma_zamani'
            ]);
        });
    }
};
