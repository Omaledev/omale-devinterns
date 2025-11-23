<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This is to override the default auth routes
      $this->configureAuthRoutes();
    }

    protected function configureAuthRoutes()
    {
        if (method_exists($this->app['router'], 'login')) {
            $this->app['router']->login = 'sign-in';
            $this->app['router']->register = 'sign-up';
            $this->app['router']->logout = 'sign-out';
        }
    }
}
