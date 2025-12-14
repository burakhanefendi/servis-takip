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
            // Servis tamamlama bilgileri
            $table->text('yapilan_islemler')->nullable()->after('teknisyenin_yorumu'); // Yapılan işlemler açıklaması
            $table->decimal('toplam_mal_hizmet_tutari', 10, 2)->nullable()->after('yapilan_islemler');
            $table->decimal('toplam_iskonto', 10, 2)->default(0)->after('toplam_mal_hizmet_tutari');
            $table->decimal('hesaplanan_kdv', 10, 2)->default(0)->after('toplam_iskonto');
            $table->decimal('vergiler_dahil_toplam', 10, 2)->nullable()->after('hesaplanan_kdv');
            
            // Servis sonuç bilgileri
            $table->text('servis_sonucu')->nullable()->after('vergiler_dahil_toplam'); // Servis sonucu açıklaması
            $table->string('periyodik_bakim')->nullable()->after('servis_sonucu'); // Aylık, 3 Aylık, 6 Aylık, Yıllık, 2 Yıllık
            $table->date('bakim_tarihi')->nullable()->after('periyodik_bakim');
            $table->boolean('sms_hatirlatma')->default(false)->after('bakim_tarihi');
            $table->string('islem_garantisi')->nullable()->after('sms_hatirlatma'); // Garanti süresi
            $table->string('odeme_yontemi')->nullable()->after('islem_garantisi'); // Nakit, Kredi Kartı, EFT, Havale
            $table->dateTime('tamamlanma_tarihi')->nullable()->after('odeme_yontemi'); // Servis tamamlanma zamanı
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servisler', function (Blueprint $table) {
            $table->dropColumn([
                'yapilan_islemler',
                'toplam_mal_hizmet_tutari',
                'toplam_iskonto',
                'hesaplanan_kdv',
                'vergiler_dahil_toplam',
                'servis_sonucu',
                'periyodik_bakim',
                'bakim_tarihi',
                'sms_hatirlatma',
                'islem_garantisi',
                'odeme_yontemi',
                'tamamlanma_tarihi'
            ]);
        });
    }
};
