<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Auth\Events\Login;
use App\Providers\SendWelcomeOnLogin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
    
    protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\SendWelcomeOnLogin::class,
    ],
];
}
