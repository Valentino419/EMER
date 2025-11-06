<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendWelcomeOnLogin
{
    public function handle($event): void
    {
     
     $user = $event->user;
    $token = env('MAILTRAP_API_TOKEN');

    $payload = json_encode([
        'from' => ['email' => 'hola@emer.com', 'name' => 'Emer App'],
        'to' => [['email' => $user->email, 'name' => $user->name]],
        'subject' => "¡Bienvenido, {$user->name}!",
        'html' => "<h1>Hola {$user->name}!</h1><p>Tu cuenta está lista.</p><a href='http://localhost/dashboard' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Ir al Dashboard</a>",
        'text' => "¡Hola {$user->name}! Tu cuenta está activa."
    ]);

    $ch = curl_init('https://send.api.mailtrap.io/api/v1/batch/send');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic ' . base64_encode($token . ':'),
            'Content-Type: application/json'
        ],
        // DESACTIVAR SSL (SOLO PARA PRUEBAS)
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        \Log::error("cURL Error: $error");
    } else {
        \Log::info("EMAIL ENVIADO A {$user->email} → VE A MAILTRAP");
    }
}
}


?>