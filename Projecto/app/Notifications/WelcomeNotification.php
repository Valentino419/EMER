<?php

namespace App\Notifications;

// use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class WelcomeNotification extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('¡Bienvenido de nuevo, ' . $notifiable->name . '!')
            ->line('Esta es tu sesión como nuevo usuario. Explora las funciones y empieza a usar la app.')
            ->action('Ver Dashboard', url('/dashboard'))
            ->line('¡Gracias por usar nuestra app!')
            ->with(['login_time' => Carbon::now()->format('Y-m-d H:i:s')]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => '¡Iniciando sesión como nuevo usuario! Hora: ' . Carbon::now()->format('Y-m-d H:i:s'),
            'action' => 'dashboard',
            'user_id' => $notifiable->id,
        ];
    }
}
