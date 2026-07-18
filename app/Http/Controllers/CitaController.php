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
        // Cargamos las relaciones para evitar problemas de N+1 y mostrar los nombres correctamente
        $citas = Cita::with(['paciente', 'servicio'])->orderBy('fecha', 'asc')->get();
        return view('citas.index', compact('citas'));
    }

    public function create()
    {
        // Necesitamos enviar los pacientes y servicios para llenar los selects del formulario
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

        // Agregamos el estado por defecto
        $validated['estado'] = 'pendiente';

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
        
        $validated = $request->validate([
            'estado' => 'required|in:pendiente,confirmada,cancelada',
        ]);

        $cita->update($validated);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }
}