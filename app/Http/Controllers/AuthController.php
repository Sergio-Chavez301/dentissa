<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // Importamos Cache

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Intentamos loguear solo si 'activo' es 1
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'activo' => 1], $request->filled('remember'))) {
            
            $request->session()->regenerate();

            // Marcamos al usuario como conectado en el cache (por 15 minutos)
            // Esto no toca tu base de datos
            Cache::put('user-is-online-' . Auth::id(), true, now()->addMinutes(15));

            $user = Auth::user();

            switch ($user->role_id) {
                case 1: // Administrador
                    return redirect()->route('dashboard.admin');
                case 2: // Asistente
                    return redirect()->route('dashboard.asistente');
                case 3: // Paciente
                    return redirect()->route('dashboard.paciente');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['username' => 'Rol no autorizado.']);
            }
        }

        return back()->withErrors([
            'username' => 'Las credenciales son incorrectas o la cuenta está desactivada.',
        ])->onlyInput('username');
    }   

    public function logout(Request $request)
    {
        // 1. Eliminamos el estado de 'conectado' del cache ANTES de cerrar sesión
        if (Auth::check()) {
            Cache::forget('user-is-online-' . Auth::id());
        }

        // 2. Cerramos sesión (SIN tocar la columna 'activo' de la base de datos)
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}