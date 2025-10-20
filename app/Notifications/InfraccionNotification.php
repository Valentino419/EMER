<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InfraccionNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail']; // Solo correo, ya que la BD la manejamos manualmente
    }

    public function toMail($notifiable)
    {
        $message = new MailMessage;
        $message->subject('Notificación de Infracción de Estacionamiento')
               ->line('Tienes una infracción registrada:')
               ->line('Patente: ' . $this->data['car_plate'])
               ->line('Fecha: ' . $this->data['date'])
               ->line('Hora: ' . $this->data['hour'])
               ->line('Ubicación: ' . $this->data['ubication'])
               ->line($this->data['message'] ?? 'Por favor, regulariza esta infracción lo antes posible.');

        return $message;
    }
}