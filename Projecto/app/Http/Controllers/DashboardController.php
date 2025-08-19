<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ? $user->role->name : 'user';
        $data = $this->getDashboardData($role);

        // Render role-specific view
        return view("dashboard.{$role}", [
            'user' => $user,
            'role' => $role,
            'data' => $data,
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
                return [
                    'title' => 'Dashboard Usuario',
                    'widgets' => [
                        ['name' => 'Ver Perfil', 'link' => route('user.profile')],
                        ['name' => 'Mis Órdenes', 'link' => route('user.orders')],
                    ],
                ];
        }
    }
}