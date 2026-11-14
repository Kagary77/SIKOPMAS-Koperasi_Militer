<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Support\Routing\PortalRoutes;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\SetKoperasiContext;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function (): void {
        // Daftarkan grup route portal SIKOPMAS
        PortalRoutes::customer(base_path('routes/customer.php'));
        PortalRoutes::tenant(base_path('routes/tenant.php'));
        PortalRoutes::admin(base_path('routes/admin.php'));
    },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
            'koperasi' => SetKoperasiContext::class, // Tambahkan ini
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
