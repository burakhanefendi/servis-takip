<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

class TenantAdminController extends Controller
{
    /**
     * Tenant yönetim sayfası
     */
    public function index()
    {
        $tenants = Tenant::orderBy('created_at', 'desc')->get();
        return view('admin.tenants.index', compact('tenants'));
    }
    
    /**
     * Tenant oluşturma formu
     */
    public function create()
    {
        return view('admin.tenants.create');
    }
    
    /**
     * Tenant oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'subdomain' => 'required|alpha_num|unique:tenants,subdomain',
            'name' => 'required|string|max:255',
            'subscription_expires' => 'nullable|date',
        ]);
        
        $subdomain = strtolower(trim($request->subdomain));
        $dbName = 'aritmapp_' . $subdomain;
        
        try {
            // 1. Veritabanı oluştur
            DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            
            // 2. Tenant kaydı ekle
            $tenant = Tenant::create([
                'subdomain' => $subdomain,
                'name' => $request->name,
                'database_name' => $dbName,
                'active' => true,
                'subscription_expires' => $request->subscription_expires,
                'notes' => $request->notes,
            ]);
            
            // 3. Migration çalıştır
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
            
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--force' => true,
            ]);
            
            return redirect()->route('admin.tenants.index')
                ->with('success', "Tenant başarıyla oluşturuldu! URL: https://{$subdomain}.aritmapp.com");
                
        } catch (\Exception $e) {
            if (isset($tenant)) {
                $tenant->delete();
            }
            
            return back()->with('error', 'Hata: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Tenant sil
     */
    public function destroy($id)
    {
        $tenant = Tenant::findOrFail($id);
        
        // Veritabanını sil (dikkatli kullanın!)
        // DB::statement("DROP DATABASE IF EXISTS `{$tenant->database_name}`");
        
        $tenant->delete();
        
        return redirect()->route('admin.tenants.index')
            ->with('success', 'Tenant silindi.');
    }
}

