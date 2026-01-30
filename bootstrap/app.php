<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // CSRF protection is disabled for API routes
        // API authentication uses Laravel Sanctum with token-based auth
        // CSRF middleware is only applied to web routes by default
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
