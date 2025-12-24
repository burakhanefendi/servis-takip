<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'subdomain',
        'name',
        'database_name',
        'logo',
        'active',
        'subscription_expires',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'subscription_expires' => 'date',
    ];

    /**
     * Subdomain'e göre tenant bul
     */
    public static function findBySubdomain($subdomain)
    {
        return self::where('subdomain', $subdomain)
                   ->where('active', true)
                   ->first();
    }

    /**
     * Tenant'ın veritabanı bağlantısını yapılandır
     */
    public function configure()
    {
        config(['database.connections.tenant' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $this->database_name,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]]);
    }

    /**
     * Tenant'ın aktif olup olmadığını kontrol et
     */
    public function isActive()
    {
        if (!$this->active) {
            return false;
        }

        if ($this->subscription_expires && $this->subscription_expires->isPast()) {
            return false;
        }

        return true;
    }
}
