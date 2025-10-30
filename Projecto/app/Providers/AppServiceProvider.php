<?php

namespace App\Providers;
use Stripe\Stripe;
use Illuminate\Support\ServiceProvider;
use MercadoPago\SDK;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void 
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }
}
