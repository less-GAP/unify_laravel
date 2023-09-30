<?php

namespace App\Providers;

use App\Services\EmailService;
use App\Services\RealtimeService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;

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
        $this->app->singleton(EmailService::class, function (Application $app) {
            return new EmailService();
        });
        $this->app->singleton(RealtimeService::class, function (Application $app) {
            return new RealtimeService();
        });
    }
}
