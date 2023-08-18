<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\ServicesContainer\ImageResolver;
use App\ServicesContainer\RequestResolver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;

class BindRequestProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->app->bind(RequestResolver::class, function (Application $app) {
            return new RequestResolver($app?->request);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        
    }
    public function provides(): array
    {
        return [RequestResolver::class];
    }
}
