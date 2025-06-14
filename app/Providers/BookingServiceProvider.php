<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\BookingService;
use App\Providers\MidtransService;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BookingService::class, function ($app) {
            return new BookingService($app->make(MidtransService::class));
        });

        $this->app->singleton(MidtransService::class, function ($app) {
            return new MidtransService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
