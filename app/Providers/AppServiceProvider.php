<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // Inisialisasi CSP Nonce agar selalu tersedia di container dan seluruh view
        if (!app()->bound('csp-nonce')) {
            app()->instance('csp-nonce', \Illuminate\Support\Str::random(32));
        }

        // Set paginator to use Bootstrap 5 styling
        Paginator::useBootstrapFive();

        // Paksa HTTPS dan Secure Cookies di environment production (agar berjalan sempurna di belakang Cloudflare/Reverse Proxy)
        if (config('app.env') === 'production' || env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
            config(['session.secure' => true]);
        }


        // Bypass SSL verification untuk Socialite Google di env local (mengatasi cURL error 60 di Windows/Laragon)
        if (config('app.env') === 'local') {
            $socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
            $socialite->extend('google', function ($app) use ($socialite) {
                $config = $app['config']['services.google'];
                return $socialite->buildProvider(\Laravel\Socialite\Two\GoogleProvider::class, $config)
                    ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            });
        }
    }
}

