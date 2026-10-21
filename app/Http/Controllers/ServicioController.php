<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        $servicios = Servicio::all();
        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
        ]);

        Servicio::create($request->all());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio registrado exitosamente.');
    }

    public function show(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.show', compact('servicio'));
    }

    public function edit(string $id)
    {
        $servicio = Servicio::findOrFail($id);
        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
        ]);

        $servicio = Servicio::findOrFail($id);
        $servicio->update($request->all());

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $servicio = Servicio::findOrFail($id);

        // Validación de integridad: No borrar si tiene citas relacionadas
        if ($servicio->citas()->exists()) {
            return redirect()->route('servicios.index')
                ->with('error', 'No se puede eliminar el servicio porque tiene citas históricas asociadas.');
        }

        $servicio->delete();

        return redirect()->route('servicios.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}