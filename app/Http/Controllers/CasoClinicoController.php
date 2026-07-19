<?php

namespace App\Http\Controllers;

use App\Models\CasoClinico;
use App\Models\Patient;
use Illuminate\Http\Request;

class CasoClinicoController extends Controller
{
    public function index()
    {
        $casos = CasoClinico::with('paciente')->orderBy('created_at', 'desc')->get();
        return view('casos.index', compact('casos'));
    }

    public function create()
    {
        $pacientes = Patient::all();
        return view('casos.create', compact('pacientes'));
    }

public function store(Request $request)
{
    $pacienteId = null;

    // Si el checkbox está marcado, el nombre 'nuevo_paciente' llegará en el request
    if ($request->has('nuevo_paciente')) {
        $validatedPaciente = $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'alergias'         => 'nullable|string',
            'enfermedades'     => 'nullable|string',
            'tratamientos'     => 'nullable|string',
        ]);
        
        $nuevoPaciente = Patient::create($validatedPaciente);
        $pacienteId = $nuevoPaciente->id;
    } else {
        // Si NO está marcado, validamos que se seleccionó un paciente existente
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id'
        ]);
        $pacienteId = $request->paciente_id;
    }

    // Validación del caso
    $validatedCaso = $request->validate([
        'tratamiento_base' => 'required|string|max:255',
        'progreso'         => 'required|integer|min:0|max:100',
    ]);

    $validatedCaso['paciente_id'] = $pacienteId;
    $validatedCaso['estado'] = CasoClinico::ESTADO_ACTIVO;

    CasoClinico::create($validatedCaso);

    return redirect()->route('casos.index')->with('success', 'Caso y paciente registrados exitosamente.');
}

    public function show(string $id)
    {
        $caso = CasoClinico::with('paciente')->findOrFail($id);
        return view('casos.show', compact('caso'));
    }

    public function edit(string $id)
    {
        $caso = CasoClinico::findOrFail($id);
        return view('casos.edit', compact('caso'));
    }

    public function update(Request $request, string $id)
    {
        $caso = CasoClinico::findOrFail($id);
        $validated = $request->validate([
            'progreso' => 'required|integer|min:0|max:100',
            'estado'   => 'required|in:' . CasoClinico::ESTADO_ACTIVO . ',' . CasoClinico::ESTADO_FINALIZADO,
        ]);
        $caso->update($validated);
        return redirect()->route('casos.index')->with('success', 'Progreso actualizado.');
    }

    public function destroy(string $id)
    {
        $caso = CasoClinico::findOrFail($id);
        $caso->delete();
        return redirect()->route('casos.index')->with('success', 'Caso eliminado.');
    }
}