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
        echo "teste";
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
