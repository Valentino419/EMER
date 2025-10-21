<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\ParkingSession;
use App\Models\Street;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ? $user->role->name : 'user';
        $data = $this->getDashboardData($role);

        // Si hay usuario autenticado, contar sus notificaciones de infracción sin leer
        $unreadCount = 0;
        if ($user) {
            $unreadCount = $user->unreadNotifications()
                ->where('type', 'App\\Notifications\\InfraccionNotification')
                ->count();
        }

        // Render role-specific view
        return view("dashboard.{$role}", [
            'user' => $user,
            'role' => $role,
            'data' => $data,
            'unreadCount' => $unreadCount,
        ]);
    }

    private function getDashboardData($role)
    {
        switch ($role) {
            case 'admin':
                return [
                    'title' => 'Dashboard Admin',
                    'widgets' => [
                        ['name' => 'Gestionar Autos', 'link' => route('cars.index')],
                        ['name' => 'Gestionar Inspectores', 'link' => route('inspectors.index')],
                        ['name' => 'Gestionar Infracciones', 'link' => route('infractions.index')],
                    ],
                ];
            case 'inspector':
                return [
                    'title' => 'Dashboard Inspector',
                    'widgets' => [
                        ['name' => 'Ver Inspecciones', 'link' => route('inspector.inspections')],
                        ['name' => 'Programar Inspecciones', 'link' => route('inspector.schedule')],
                    ],
                ];
            case 'user':
            default:
                $cars = Car::where('user_id', Auth::id())->get();
                $zones = Zone::all();
                $streets = Street::all();
                $activeSessions = ParkingSession::where('user_id', Auth::id())
                    ->where('status', 'active')
                    ->with(['car', 'street.zone'])
                    ->get();

                return [
                    'title' => 'Dashboard Usuario',
                    'widgets' => [
                        ['name' => 'Mis Autos', 'link' => route('cars.index')],
                        ['name' => 'Iniciar Estacionamiento', 'link' => route('parking.create')],
                        ['name' => 'Multas', 'link' => route('infractions.index')],
                        ['name' => 'Zonas', 'link' => route('zone.index')],
                        ['name' => 'Historial de Estacionamientos', 'link' => route('parking.show')],
                    ],
                    'cars' => $cars,
                    'zones' => $zones,
                    'streets' => $streets,
                    'activeSessions' => $activeSessions,
                ];
        }
    }
}
