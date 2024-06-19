<?php

namespace App\Providers;

use App\HTTPClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $baseUrl = config('services.wp.base_url');

        $this->app->bind(HTTPClient::class, function ($app) use ($baseUrl) {
            return new HTTPClient($baseUrl);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
