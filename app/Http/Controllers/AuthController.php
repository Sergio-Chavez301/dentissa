<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validar las credenciales usando 'username' para coincidir con tu tabla
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Intentar loguear al usuario
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Obtener el usuario autenticado
            $user = Auth::user();

            // 3. Redirección dinámica basada en su "role_id"
            switch ($user->role_id) {
                case 1: // Administrador
                    return redirect()->route('dashboard.admin');
                case 2: // Asistente
                    return redirect()->route('dashboard.asistente');
                case 3: // Paciente
                    return redirect()->route('dashboard.paciente');
                default:
                    // Si por algún motivo tiene un rol no registrado
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['username' => 'Rol no autorizado.']);
            }
        }

        // Si las credenciales fallan
        return back()->withErrors([
            'username' => 'El usuario o la contraseña son incorrectos.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}