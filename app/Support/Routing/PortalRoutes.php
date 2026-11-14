<?php

namespace App\Support\Routing;

use Closure;
use Illuminate\Support\Facades\Route;

final class PortalRoutes
{
    /**
     * Portal Pelanggan (Publik, Anonim). Prefix: kantin/{koperasi}
     */
    public static function customer(Closure|string $routes): void
    {
        Route::middleware('web')
            ->prefix('koperasi/{koperasi}')
            ->name('customer.')
            ->group($routes);
    }

    /**
     * Portal Operator Koperasi (Kasir/Admin Toko).
     * Prefix: tenant/{koperasi} (menggunakan slug koperasi)
     */
    public static function tenant(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'role:koperasi'])
            ->prefix('tenant/{koperasi}')
            ->name('tenant.')
            ->group($routes);
    }

    /**
     * Portal Pusat (Super Admin/Pengelola Pusat).
     */
     public static function admin(Closure|string $routes): void
    {
        Route::middleware(['web', 'auth', 'verified', 'role:pusat'])
            ->prefix('admin')
            ->name('admin.')
            ->group($routes);
    }
}