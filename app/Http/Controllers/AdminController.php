<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Servicio;
use App\Models\Paciente; // <-- Agregamos el modelo Paciente
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Datos para las tarjetas
        $citasHoyCount = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientesCount = Paciente::count();
        $casosActivosCount = CasoClinico::where('estado', 'activo')->count();
        $serviciosCount = Servicio::count();

        // 2. Citas del día de hoy con sus relaciones correctas
        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->with(['paciente', 'servicio']) 
            ->orderBy('hora', 'asc')
            ->get();

        // 3. Casos Clínicos Activos
        $casosActivos = CasoClinico::where('estado', 'activo')
            ->with('paciente')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 4. Últimos 5 pacientes registrados en la plataforma
        $ultimosPacientes = Paciente::latest() 
            ->take(5)
            ->get();

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