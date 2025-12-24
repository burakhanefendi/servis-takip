<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class ListTenants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tüm tenant\'ları listele';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::orderBy('created_at', 'desc')->get();
        
        if ($tenants->isEmpty()) {
            $this->warn('❌ Henüz tenant oluşturulmamış.');
            $this->info('Yeni tenant oluşturmak için: php artisan tenant:create <subdomain> <name>');
            return 0;
        }
        
        $this->info("\n🏢 TENANT LİSTESİ\n");
        
        $headers = ['ID', 'Subdomain', 'Firma Adı', 'Veritabanı', 'Durum', 'Oluşturma'];
        $rows = [];
        
        foreach ($tenants as $tenant) {
            $status = $tenant->isActive() ? '✅ Aktif' : '❌ Pasif';
            
            $rows[] = [
                $tenant->id,
                $tenant->subdomain,
                $tenant->name,
                $tenant->database_name,
                $status,
                $tenant->created_at->format('d.m.Y H:i'),
            ];
        }
        
        $this->table($headers, $rows);
        
        $this->newLine();
        $this->info("📊 Toplam: " . $tenants->count() . " tenant");
        $this->info("✅ Aktif: " . $tenants->filter->isActive()->count());
        $this->info("❌ Pasif: " . $tenants->reject->isActive()->count());
        
        return 0;
    }
}
