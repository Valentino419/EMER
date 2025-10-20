<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Infraction;
use App\Models\Car;
use App\Notifications\InfraccionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mostrar todas las infracciones de los usuarios (para administradores/inspectores)
     */
    public function index()
    {
        $users = User::whereHas('infractions')->with('infractions')->get();
        $cars= Car::All();
        return view('dashboard.user', compact('users', 'cars'));
    }

    /**
     * Mostrar las notificaciones del usuario logueado
     */
    public function userNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->where('type', InfraccionNotification::class)->get();

        // Marcar notificaciones como leídas
        $user->unreadNotifications()->where('type', InfraccionNotification::class)->update(['read_at' => now()]);

        return view('notifications.user', compact('notifications'));
    }

    /**
     * Almacenar una nueva infracción y notificar al usuario
     */
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_plate' => 'required|string|max:20',
            'date' => 'required|date',
            'hour' => 'required|date_format:H:i',
            'ubication' => 'required|string|max:255',
        ]);

        // Crear la infracción
        $infraction = Infraction::create($validated);

        // Notificar al usuario
        $this->notifyUser($infraction);

        return redirect()->route('notifications.index')->with('success', 'Infracción registrada y notificación enviada.');
    }

    /**
     * Enviar notificación manual de una infracción específica
     */
    public function sendInfraccion($infraccionId)
    {
        $infraction = Infraction::with('user')->findOrFail($infraccionId);

        $infraction->user->notify(new InfraccionNotification([
            'car_plate' => $infraction->car_plate,
            'date' => $infraction->date,
            'hour' => $infraction->hour,
            'ubication' => $infraction->ubication,
            'infraccion_id' => $infraction->id,
        ]));

        return back()->with('success', 'Notificación enviada para la infracción ' . $infraction->car_plate);
    }

    /**
     * Enviar notificaciones de todas las infracciones de un usuario
     */
    public function sendUserInfracciones($userId)
    {
        $user = User::with('infractions')->findOrFail($userId);

        foreach ($user->infractions as $infraction) {
            $user->notify(new InfraccionNotification([
                'car_plate' => $infraction->car_plate,
                'date' => $infraction->date,
                'hour' => $infraction->hour,
                'ubication' => $infraction->ubication,
                'infraccion_id' => $infraction->id,
            ]));
        }

        return back()->with('success', 'Todas las notificaciones fueron enviadas a ' . $user->name);
    }

    /**
     * Función para enviar notificación al usuario
     */
    protected function notifyUser(Infraction $infraction)
    {
        $user = $infraction->user;

        $user->notify(new InfraccionNotification([
            'car_plate' => $infraction->car_plate,
            'date' => $infraction->date,
            'hour' => $infraction->hour,
            'ubication' => $infraction->ubication,
            'infraccion_id' => $infraction->id,
        ]));
    }
}