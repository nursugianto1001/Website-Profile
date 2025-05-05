<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MidtransService::class, function ($app) {
            return new MidtransService();
        });

        $this->app->singleton(BookingService::class, function ($app) {
            return new BookingService(
                $app->make(MidtransService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
