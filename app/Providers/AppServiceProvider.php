<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;

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
        Paginator::useBootstrapFive();
        
        // Share iframe detection with all views
        View::composer('*', function ($view) {
            $isIframe = request()->header('Sec-Fetch-Dest') === 'iframe';
            $view->with('isIframe', $isIframe);
        });
    }
}
