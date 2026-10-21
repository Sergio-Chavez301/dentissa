<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Patient;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role_id == 3) {
            return redirect()->route('dashboard.paciente');
        }

        $totalCitas = Cita::count();
        $citasHoy = Cita::whereDate('fecha', Carbon::today())->count();
        $pacientesActivos = Patient::count();
        $casosActivos = CasoClinico::where('estado', 'activo')->count();
        $serviciosActivos = Servicio::count();
        $ingresosEstimados = Cita::with('servicio')
            ->get()
            ->sum(fn ($cita) => (float) ($cita->servicio?->precio ?? 0));

        $citasPorEstado = [
            'en_espera' => Cita::where('estado', 'en_espera')->count(),
            'confirmada' => Cita::where('estado', 'confirmada')->count(),
            'realizada' => Cita::where('estado', 'realizada')->count(),
            'cancelada' => Cita::where('estado', 'cancelada')->count(),
            'no_presento' => Cita::where('estado', 'no_presento')->count(),
        ];

        $ultimasCitas = Cita::with(['paciente', 'servicio'])
            ->latest('fecha')
            ->take(8)
            ->get();

        return view('reportes.index', compact(
            'totalCitas',
            'citasHoy',
            'pacientesActivos',
            'casosActivos',
            'serviciosActivos',
            'ingresosEstimados',
            'citasPorEstado',
            'ultimasCitas'
        ));
    }
}
