<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::with('role')->get();
        return view('users.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:usuarios,username',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'role_id' => 'required|integer|in:1,2,3,4', 
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.'
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password), 
            'activo' => $request->has('activo') ? 1 : 0, 
        ]);

        return redirect()->route('users.index')
            ->with('success', 'El usuario ha sido registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $usuario = User::with('role')->findOrFail($id);
        return view('users.showUser', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Se elimina la línea que forzaba 'activo' a 1 para respetar la BD
        $usuario = User::findOrFail($id);
        return view('users.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:usuarios,username,' . $usuario->id,
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'role_id' => 'required|integer|in:1,2,3,4',
            'password' => 'nullable|string|min:8|confirmed', 
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.'
        ]);

        // Preparamos los datos a actualizar
        $data = [
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            // Si el checkbox no viene marcado, el valor es 0 (bloqueado)
            'activo' => $request->has('activo') ? 1 : 0, 
        ];

        // Solo actualizamos password si se escribió algo
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Los datos del usuario han sido actualizados.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);

        if (Auth::id() === $usuario->id) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario mientras tienes la sesión activa.');
        }

        $usuario->delete();

        return redirect()->route('users.index')
            ->with('success', 'El usuario ha sido eliminado correctamente del sistema.');
    }
}