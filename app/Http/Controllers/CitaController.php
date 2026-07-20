<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $citas = Cita::with(['paciente', 'servicio'])
            ->orderByRaw("CASE WHEN estado = '" . Cita::ESTADO_EN_ESPERA . "' THEN 0 ELSE 1 END ASC, fecha ASC, hora ASC")
            ->get();
        return view('citas.index', compact('citas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = Patient::all();
        $servicios = Servicio::all();
        return view('citas.create', compact('pacientes', 'servicios'));
    }

    /**
     * Endpoint para consultar disponibilidad
     */
    public function verificarDisponibilidad(Request $request)
    {
        $fecha = $request->query('fecha');
        if (!$fecha) return response()->json(['horarios' => []]);
        $horarios = Cita::getHorariosDisponibles($fecha);
        return response()->json(['horarios' => array_values($horarios)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Lógica para manejar paciente nuevo o existente
        if ($request->has('nuevo_paciente')) {
            $datosPaciente = $request->validate([
                'nombre'           => 'required|string|max:255',
                'apellidos'        => 'required|string|max:255',
                'telefono'         => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'alergias'         => 'nullable|string',
                'enfermedades'     => 'nullable|string',
                'tratamientos'     => 'nullable|string',
            ]);
            $paciente = Patient::create($datosPaciente);
            $paciente_id = $paciente->id;
        } else {
            $request->validate(['paciente_id' => 'required|exists:pacientes,id']);
            $paciente_id = $request->paciente_id;
        }

        // 2. Validación de cita
        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        // 3. Validación de disponibilidad en tiempo real
        $horariosDisponibles = Cita::getHorariosDisponibles($validated['fecha']);
        if (!in_array($validated['hora'], $horariosDisponibles)) {
            return back()->withInput()->with('error', 'Lo sentimos, este horario acaba de ser ocupado.');
        }

        // 4. Crear la cita
        Cita::create([
            'paciente_id' => $paciente_id,
            'servicio_id' => $validated['servicio_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'estado' => Cita::ESTADO_EN_ESPERA
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cita = Cita::with(['paciente', 'servicio'])->findOrFail($id);
        return view('citas.show', compact('cita'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cita = Cita::findOrFail($id);
        return view('citas.edit', compact('cita'));
    }

public function update(Request $request, string $id)
{
    $cita = Cita::findOrFail($id);
    
    $request->validate([
        'fecha'  => 'required|date',
        'hora'   => 'required',
        'estado' => 'required',
    ]);

    // Verificamos disponibilidad SOLO si la fecha o la hora han cambiado
    if ($request->fecha !== $cita->fecha || $request->hora !== $cita->hora) {
        $horariosDisponibles = Cita::getHorariosDisponibles($request->fecha);
        
        // Si el horario nuevo NO está en la lista de disponibles, bloqueamos
        if (!in_array($request->hora, $horariosDisponibles)) {
            return back()->withInput()->with('error', 'El horario seleccionado no está disponible.');
        }
    }

    $cita->update([
        'fecha'  => $request->fecha,
        'hora'   => $request->hora,
        'estado' => $request->estado,
    ]);

    return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
}
}