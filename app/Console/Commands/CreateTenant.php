<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create 
                            {subdomain : Subdomain adı (örn: kapakli)} 
                            {name : Firma adı (örn: "Kapaklı Su Arıtma")}
                            {--expires= : Abonelik bitiş tarihi (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Yeni tenant (müşteri) oluştur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subdomain = $this->argument('subdomain');
        $name = $this->argument('name');
        $expires = $this->option('expires');
        
        // Subdomain'i temizle
        $subdomain = strtolower(trim($subdomain));
        $subdomain = preg_replace('/[^a-z0-9]/', '', $subdomain);
        
        if (empty($subdomain)) {
            $this->error('❌ Geçersiz subdomain!');
            return 1;
        }
        
        // Veritabanı adı
        $dbName = 'aritmapp_' . $subdomain;
        
        $this->info("🏢 Yeni Tenant Oluşturuluyor...");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("📌 Subdomain: {$subdomain}");
        $this->info("📌 Firma Adı: {$name}");
        $this->info("📌 Veritabanı: {$dbName}");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        
        // Subdomain kontrolü
        if (Tenant::where('subdomain', $subdomain)->exists()) {
            $this->error('❌ Bu subdomain zaten kullanılıyor!');
            return 1;
        }
        
        try {
            // 1. Veritabanı oluştur
            $this->info("\n📊 Veritabanı oluşturuluyor...");
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("✅ Veritabanı oluşturuldu");
            
            // 2. Tenant kaydı ekle
            $this->info("\n💾 Tenant kaydı ekleniyor...");
            $tenant = Tenant::create([
                'subdomain' => $subdomain,
                'name' => $name,
                'database_name' => $dbName,
                'active' => true,
                'subscription_expires' => $expires,
            ]);
            $this->info("✅ Tenant kaydı eklendi (ID: {$tenant->id})");
            
            // 3. Tenant veritabanına bağlan
            $this->info("\n🔨 Migration çalıştırılıyor...");
            
            config(['database.connections.tenant' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $dbName,
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]]);
            
            // Migration çalıştır (tenants tablosu hariç)
            $migrations = $this->getMigrationsExcept(['create_tenants_table']);
            
            foreach ($migrations as $migration) {
                $this->info("   → {$migration}");
            }
            
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force' => true,
                '--path' => 'database/migrations',
            ]);
            
            $this->info("✅ Migration tamamlandı");
            
            // 4. Başarı mesajı
            $this->info("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("🎉 Tenant başarıyla oluşturuldu!");
            $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
            $this->info("🌐 URL: https://{$subdomain}.aritmapp.com");
            $this->info("📊 Veritabanı: {$dbName}");
            
            if ($expires) {
                $this->info("⏰ Abonelik Bitiş: {$expires}");
            }
            
            $this->newLine();
            $this->warn("⚠️  cPanel'den subdomain oluşturmayı unutmayın!");
            $this->info("   Subdomain: {$subdomain}.aritmapp.com");
            $this->info("   Document Root: public_html/laravel-app/public");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("\n❌ Hata oluştu: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            
            // Rollback: Tenant kaydını sil
            if (isset($tenant)) {
                $tenant->delete();
                $this->warn("⚠️  Tenant kaydı geri alındı");
            }
            
            return 1;
        }
    }
    
    /**
     * Belirli migration'lar hariç tüm migration'ları al
     */
    private function getMigrationsExcept($except = [])
    {
        $path = database_path('migrations');
        $files = scandir($path);
        
        $migrations = [];
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $skip = false;
            foreach ($except as $ex) {
                if (strpos($file, $ex) !== false) {
                    $skip = true;
                    break;
                }
            }
            
            if (!$skip) {
                $migrations[] = pathinfo($file, PATHINFO_FILENAME);
            }
        }
        
        return $migrations;
    }
}
