<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Active CORS
        $middleware->append(HandleCors::class);

        // 🔥 Configuration CORS directement ici (Laravel 12)
        $middleware->group('api', [
            HandleCors::class => [
                'allowed_origins' => [
                    'https://tp-frontend-tau.vercel.app',
                    'http://localhost:5500',
                    'http://127.0.0.1:5500',
                ],
                'allowed_methods' => ['*'],
                'allowed_headers' => ['*'],
                'supports_credentials' => true,
            ],
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {})
    ->create();
