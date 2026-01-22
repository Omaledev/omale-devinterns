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
        // Global Middleware (Runs on every request)
        $middleware->web(append: [
            \App\Http\Middleware\SetActiveSchool::class,
            \App\Http\Middleware\CheckSchoolStatus::class,
        ]);

        // API middleware
        // Runs only on API routes  
        $middleware->api(append: [
            \App\Http\Middleware\EnsureSchoolIsSelected::class, 
            // Add other API-specific middleware here
        ]);
        

        // Middleware Aliases (Used in Routes)
        $middleware->alias([
            // Using Spatie package
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            
            // custom aliases
            'set_school' => \App\Http\Middleware\SetActiveSchool::class, 
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();