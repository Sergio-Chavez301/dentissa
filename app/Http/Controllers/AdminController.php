<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Servicio;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Datos para las tarjetas (Opcional: puedes filtrar también el contador si quieres que coincida con la tabla)
        $citasHoyCount = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientesCount = Patient::count();
        $casosActivosCount = CasoClinico::where('estado', 'activo')->where('progreso', '<', 100)->count();
        $serviciosCount = Servicio::count();

        // 2. Citas del día de hoy con sus relaciones correctas
        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->with(['paciente', 'servicio']) 
            ->orderBy('hora', 'asc')
            ->get();

        // 3. Casos Clínicos Activos (FILTRANDO los que ya llegaron al 100%)
        $casosActivos = CasoClinico::where('estado', 'activo')
            ->where('progreso', '<', 100) // <--- Filtro aplicado aquí
            ->with('paciente')
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 4. Últimos 5 pacientes registrados en la plataforma
        $ultimosPacientes = Patient::latest() 
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

    public function asistente()
    {
        // Datos para las tarjetas
        $citas = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientes = Patient::count();
        $casos = CasoClinico::where('estado', 'activo')->where('progreso', '<', 100)->count(); // Opcional para el contador
        $servicios = Servicio::count();

        // Datos para las tablas
        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->with(['paciente', 'servicio'])
            ->orderBy('hora', 'asc')
            ->get();

        // Casos Clínicos Activos (FILTRANDO los que ya llegaron al 100%)
        $casosActivos = CasoClinico::where('estado', 'activo')
            ->where('progreso', '<', 100) // <--- Filtro aplicado aquí también
            ->with('paciente')
            ->latest('updated_at')
            ->take(5)
            ->get();

        $ultimosPacientes = Patient::latest()->take(5)->get();

        return view('dashboard.asistente', compact(
            'citas',
            'pacientes',
            'casos',
            'servicios',
            'citasHoy',
            'casosActivos',
            'ultimosPacientes' 
        ));
    }
}