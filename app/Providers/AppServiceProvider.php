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
    public function boot()
    {
        config(['app.locale' => 'id']); // Set locale to Indonesian
        \Carbon\Carbon::setLocale('id'); // Set Carbon's locale to Indoensian
        if(config('app.env') !== 'local') {
            \URL::forceScheme('https');
        }
    }
}
