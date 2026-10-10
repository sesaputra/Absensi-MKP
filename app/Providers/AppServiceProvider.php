<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // VPS runs behind Caddy TLS termination. Force https URLs in
        // production so forms/assets never render with an http:// action.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
