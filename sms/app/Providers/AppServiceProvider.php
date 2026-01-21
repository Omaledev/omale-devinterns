<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\AcademicSession;
use App\Models\Term;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;

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

        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        // This is to override the default auth routes
        $this->configureAuthRoutes();
        
        // Share active session and term with all views
        $this->shareActiveSessionAndTerm();

         Paginator::useBootstrapFive();
    }

    protected function configureAuthRoutes()
    {
        if (method_exists($this->app['router'], 'login')) {
            $this->app['router']->login = 'sign-in';
            $this->app['router']->register = 'sign-up';
            $this->app['router']->logout = 'sign-out';
        }
    }

    protected function shareActiveSessionAndTerm()
    {
        // Share with all views
        View::composer('*', function ($view) {
            // Only if user is logged in
            if (auth()->check()) {
                $schoolId = auth()->user()->school_id;
                
                $activeSession = AcademicSession::where('is_active', true)
                    ->where('school_id', $schoolId)
                    ->first();
                
                $activeTerm = Term::where('is_active', true)
                    ->whereHas('academicSession', function($query) use ($schoolId) {
                        $query->where('school_id', $schoolId);
                    })
                    ->first();
                
                $view->with([
                    'activeSession' => $activeSession,
                    'activeTerm' => $activeTerm,
                ]);
            } else {
                $view->with([
                    'activeSession' => null,
                    'activeTerm' => null,
                ]);
            }
        });
    }
}