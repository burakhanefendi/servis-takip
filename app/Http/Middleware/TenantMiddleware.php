<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Host'u al
        $host = $request->getHost();
        
        // Subdomain'i çıkar
        $parts = explode('.', $host);
        $subdomain = $parts[0];
        
        // Ana domain veya www kontrolü
        if (in_array($subdomain, ['aritmapp', 'www', 'localhost', '127'])) {
            // Ana domain'e yönlendir veya admin panel göster
            return $this->handleMainDomain($request, $next);
        }
        
        // Tenant'ı bul
        $tenant = Tenant::findBySubdomain($subdomain);
        
        if (!$tenant) {
            abort(404, 'Müşteri bulunamadı. Lütfen sistem yöneticisi ile iletişime geçin.');
        }
        
        if (!$tenant->isActive()) {
            return response()->view('errors.tenant-inactive', ['tenant' => $tenant], 403);
        }
        
        // Tenant bilgilerini session'a kaydet
        session([
            'tenant_id' => $tenant->id,
            'tenant_name' => $tenant->name,
            'tenant_subdomain' => $subdomain,
            'tenant_database' => $tenant->database_name,
        ]);
        
        // View'larda kullanmak için share et
        view()->share('currentTenant', $tenant);
        
        // Tenant veritabanına bağlan
        $tenant->configure();
        DB::purge('mysql');
        Config::set('database.default', 'tenant');
        DB::reconnect('tenant');
        
        return $next($request);
    }
    
    /**
     * Ana domain için özel işlem
     */
    private function handleMainDomain(Request $request, Closure $next)
    {
        // Ana domain için admin panel veya landing page göster
        // Şimdilik normal akışa devam et (default DB kullanılır)
        return $next($request);
    }
}
