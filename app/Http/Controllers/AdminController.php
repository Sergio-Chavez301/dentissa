<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Muestra el panel de administración principal.
     */
    public function index()
    {
        // 1. Datos para los 4 bloques del componente "Datos Rápidos"
        $citasHoyCount = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientesCount = User::where('role_id', 3)->count(); // Rol 3 = Paciente
        $casosActivosCount = CasoClinico::where('estado', 'activo')->count();
        $serviciosCount = Servicio::count();

        // 2. Citas del día de hoy con sus relaciones de paciente (user) y tratamiento (servicio)
        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->with(['user', 'servicio'])
            ->orderBy('hora', 'asc')
            ->get();

        // 3. Casos Clínicos Activos (los últimos 5 con actualizaciones más recientes)
        $casosActivos = CasoClinico::where('estado', 'activo')
            ->with('user')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 4. Últimos 5 pacientes (role_id: 3) que se registraron en la plataforma
        $ultimosPacientes = User::where('role_id', 3)
            ->latest()
            ->take(5)
            ->get();

        // Mandamos todo bien empaquetado a la vista 'dashboard.admin'
        return view('dashboard.admin', compact(
            'citasHoyCount',
            'pacientesCount',
            'casosActivosCount',
            'serviciosCount',
            'citasHoy',
            'casosActivos',
            'ultimosPacientes'
        ));
    }
}