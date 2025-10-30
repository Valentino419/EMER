<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Log;

class SendWelcomeOnLogin
{
    use InteractsWithQueue; 
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        Log::info('EVENTO LOGIN DETECTADO', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'created_at' => $event->user->created_at,
        ]);
        
        $user = $event->user;
        
        $user->notify(new WelcomeNotification());
        
        
        
        
        // Verificar si es un usuario nuevo (por ejemplo, si es su primer login)
        
        // if ($user->created_at->diffInMinutes(now()) < 5) {
        //     $user->notify(new WelcomeNotification());
        // }
        // $user->created_at= now();
        // $user->save();
    }
}
