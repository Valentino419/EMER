<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Providers\SendWelcomeOnLogin;
use Stripe\Stripe;
use MercadoPago\SDK;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    public function boot(): void 
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
    
    protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\SendWelcomeOnLogin::class,
    ],
];
}
