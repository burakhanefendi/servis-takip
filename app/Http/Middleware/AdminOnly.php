<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * Sadece ana domain'den (admin) erişime izin verir
     * Tenant subdomain'lerinden erişimi engeller
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Eğer tenant session varsa (subdomain'deyiz demektir)
        if (session()->has('tenant_id')) {
            abort(403, 'Bu sayfaya erişim yetkiniz yok. Sadece sistem yöneticileri erişebilir.');
        }
        
        return $next($request);
    }
}
