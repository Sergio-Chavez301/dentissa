<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Patient;
use App\Models\Cita;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('role')->get();
        return view('users.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:usuarios,username',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'role_id' => 'required|exists:roles,id',
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
            'activo' => 1, 
        ]);

        return redirect()->route('users.index')
            ->with('success', 'El usuario ha sido registrado exitosamente.');
    }

    public function show(string $id)
    {
        $usuario = User::with('role')->findOrFail($id);
        return view('users.showUser', compact('usuario'));
    }

    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);
        $roles = Role::all();
        return view('users.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:usuarios,username,' . $usuario->id,
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed', 
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.'
        ]);

        $data = [
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'activo' => $request->has('activo') ? 1 : 0, 
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('usuarios')->where('id', $usuario->id)->update($data);

        return redirect()->route('users.index')
            ->with('success', 'Los datos del usuario han sido actualizados.');
    }

    public function showProfile()
    {
        $usuario = User::with('role')->find(Auth::id());
        return view('users.profile', compact('usuario'));
    }

    public function updateProfile(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:usuarios,username,' . $usuario->id,
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'username' => $request->username,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('usuarios')->where('id', $usuario->id)->update($data);

        return redirect()->route('profile.show')->with('success', 'Tu perfil ha sido actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);

        if (Auth::id() === $usuario->id) {
            return redirect()->route('users.index')
                ->with('error', 'No puedes eliminar tu propio usuario mientras tienes la sesión activa.');
        }

        $patient = Patient::where('user_id', $usuario->id)->first();

        if ($patient) {
            $patient->casosClinicos()->delete();

            Cita::where('paciente_id', $patient->id)->delete();

            $patient->delete();
        }

        $usuario->delete();

        return redirect()->route('users.index')
            ->with('success', 'El usuario, su perfil de paciente, citas y casos clínicos han sido eliminados correctamente del sistema.');
    }
}