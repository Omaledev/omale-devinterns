<?php

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
        // Adding SetActiveSchool to global web middleware (runs on every request)
        $middleware->web(append: [
            \App\Http\Middleware\SetActiveSchool::class,
        ]);

        // Alias middleware for specific route protection
        $middleware->alias([
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'permission' => \App\Http\Middleware\RoleMiddleware::class,
         \App\Http\Middleware\SetActiveSchool::class,
        //   'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        //     'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        //     'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
             // Other middleware aliases here too
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
