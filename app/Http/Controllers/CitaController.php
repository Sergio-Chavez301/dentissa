<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index()
        {

            $citas = Cita::with(['paciente', 'servicio'])
                ->orderByRaw("
                    CASE 
                        WHEN estado = '" . Cita::ESTADO_EN_ESPERA . "' THEN 0 
                        ELSE 1 
                    END ASC, 
                    fecha ASC, 
                    hora ASC
                ")
                ->get();

            return view('citas.index', compact('citas'));
        }

    public function create()
    {
        $pacientes = Patient::all();
        $servicios = Servicio::all();
        return view('citas.create', compact('pacientes', 'servicios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        // Usamos la constante definida en el modelo
        $validated['estado'] = Cita::ESTADO_EN_ESPERA;

        Cita::create($validated);

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function show(string $id)
    {
        $cita = Cita::with(['paciente', 'servicio'])->findOrFail($id);
        return view('citas.show', compact('cita'));
    }

    public function edit(string $id)
    {
        $cita = Cita::findOrFail($id);
        return view('citas.edit', compact('cita'));
    }

    public function update(Request $request, string $id)
    {
        $cita = Cita::findOrFail($id);
        
        // Validamos usando las constantes para mayor seguridad
        $request->validate([
            'estado' => 'required|in:' . Cita::ESTADO_REALIZADA . ',' . Cita::ESTADO_CANCELADA,
        ]);

        $cita->update(['estado' => $request->estado]);

        $mensaje = $request->estado === Cita::ESTADO_REALIZADA 
            ? 'Cita marcada como realizada correctamente.' 
            : 'Cita cancelada correctamente.';

        return redirect()->route('citas.index')->with('success', $mensaje);
    }
}