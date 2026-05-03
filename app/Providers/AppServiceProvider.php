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
        if ($this->app->runningInConsole()) {
            return;
        }

        $request = request();

        if ($request->headers->has('x-forwarded-proto')) {
            URL::forceScheme($request->headers->get('x-forwarded-proto'));
        }

        $host = $request->getHost();
        if ($host) {
            URL::forceRootUrl($request->getSchemeAndHttpHost());
        }
    }
}
