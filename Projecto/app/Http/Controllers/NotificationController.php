<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')->get();
        return view('notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::all();
        return view('notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);

        Notification::create($request->all());

        return redirect()->route('notifications.index')->with('success', 'Notificación creada correctamente.');
    }

    public function delete(Notification $notification)
    {
        return view('notifications.delete', compact('notification'));
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('notifications.index')->with('success', 'Notificación eliminada correctamente.');
    }
}
