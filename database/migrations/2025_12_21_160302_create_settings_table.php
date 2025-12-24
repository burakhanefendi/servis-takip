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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('firma_adi')->nullable();
            $table->string('telefon_1')->nullable();
            $table->string('telefon_2')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('adres')->nullable();
            $table->string('il')->nullable();
            $table->string('ilce')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });
        
        // Varsayılan ayarları ekle
        DB::table('settings')->insert([
            'firma_adi' => 'KAPAKLI SAĞLIKLI SU ARITMA',
            'telefon_1' => '05427173942',
            'telefon_2' => '05368332242',
            'email' => 'derensulemm@gmail.com',
            'adres' => 'CUMHURİYET MAH ÇAMBAZLAR CAD NO 46',
            'il' => 'TEKİRDAĞ',
            'ilce' => 'KAPAKLI',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
