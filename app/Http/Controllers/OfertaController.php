<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\OfficeSetting;
use Illuminate\Http\Request;

class OfertaController extends Controller
{
    public function index()
    {
        $ofertas = Oferta::orderBy('orden')->orderBy('id')->get();

        return view('ofertas.index', compact('ofertas'));
    }

    public function create()
    {
        return view('ofertas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        Oferta::create([
            'titulo' => $validated['titulo'],
            'tag' => $validated['tag'] ?? 'Promoción',
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'] ?? null,
            'orden' => $validated['orden'] ?? 0,
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.ofertas.index')->with('success', 'Oferta creada correctamente.');
    }

    public function edit(Oferta $oferta)
    {
        return view('ofertas.edit', compact('oferta'));
    }

    public function update(Request $request, Oferta $oferta)
    {
        $validated = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'tag' => ['nullable', 'string', 'max:100'],
            'descripcion' => ['required', 'string'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'activo' => ['nullable', 'boolean'],
        ]);

        $oferta->update([
            'titulo' => $validated['titulo'],
            'tag' => $validated['tag'] ?? 'Promoción',
            'descripcion' => $validated['descripcion'],
            'precio' => $validated['precio'] ?? null,
            'orden' => $validated['orden'] ?? 0,
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()->route('admin.ofertas.index')->with('success', 'Oferta actualizada correctamente.');
    }

    public function destroy(Oferta $oferta)
    {
        $oferta->delete();

        return redirect()->route('admin.ofertas.index')->with('success', 'Oferta eliminada correctamente.');
    }

    public function publicIndex()
    {
        $ofertas = Oferta::where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->get();

        $officeSettings = OfficeSetting::current();

        return view('web.ofertas', compact('ofertas', 'officeSettings'));
    }
}
