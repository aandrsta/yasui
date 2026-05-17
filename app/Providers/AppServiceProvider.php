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
    public function boot(): void
    {
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
