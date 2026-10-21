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
        $citasHoyCount = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientesCount = Patient::count();
        $casosActivosCount = CasoClinico::where('estado', 'activo')->where('progreso', '<', 100)->count();
        $serviciosCount = Servicio::count();

        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->with(['paciente', 'servicio']) 
            ->orderBy('hora', 'asc')
            ->get();

        $casosActivos = CasoClinico::where('estado', 'activo')
            ->where('progreso', '<', 100)
            ->with('paciente')
            ->latest('updated_at')
            ->take(5)
            ->get();

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
        $citas = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientes = Patient::count();
        $casos = CasoClinico::where('estado', 'activo')->where('progreso', '<', 100)->count();
        $servicios = Servicio::count();

        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->with(['paciente', 'servicio'])
            ->orderBy('hora', 'asc')
            ->get();

        $casosActivos = CasoClinico::where('estado', 'activo')
            ->where('progreso', '<', 100)
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