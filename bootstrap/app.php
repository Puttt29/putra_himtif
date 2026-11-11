<?php

use App\Http\Middleware\AuthMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Percayai semua proxy dari localtunnel / cloudflare
        $middleware->trustProxies(at: '*');

        // Alias middleware (Laravel 11 replacement for Kernel.php)
        $middleware->alias([
            // sesuaikan dengan route Anda
            'auth.middleware' => AuthMiddleware::class,

            // OPSIONAL (lebih rapi & disarankan)
            // 'role' => AuthMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
