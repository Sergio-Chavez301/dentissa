<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::all();
        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string',
            'enfermedades' => 'nullable|string',
            'tratamientos' => 'nullable|string',
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Paciente registrado exitosamente.');
    }

    // ACTUALIZADO: Carga las relaciones para la vista show
    public function show(string $id)
    {
        $patient = Patient::with(['citas', 'casosClinicos'])->findOrFail($id);
        return view('patients.show', compact('patient'));
    }

    public function edit(string $id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string',
            'enfermedades' => 'nullable|string',
            'tratamientos' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Datos actualizados correctamente.');
    }

    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente eliminado correctamente.');
    }
}