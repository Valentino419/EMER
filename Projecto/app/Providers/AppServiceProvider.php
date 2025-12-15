<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    public function boot(): void
    {
       
        $this->commands([
            \App\Console\Commands\UpdateExpiredSessions::class,
        ]);
    }

    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\SendWelcomeOnLogin::class,
        ],
    ];
}
