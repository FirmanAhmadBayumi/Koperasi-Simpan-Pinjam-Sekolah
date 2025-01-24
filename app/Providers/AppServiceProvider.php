<?php

namespace App\Providers;

use App\Models\TransaksiPokok;
use Illuminate\Support\ServiceProvider;
use App\Observers\TransaksiPokokObserver;

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
    }
}
