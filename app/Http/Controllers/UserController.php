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
        $roles = Role::whereIn('id', [1, 2])->get();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:usuarios,username',
            'email' => 'required|string|email|max:255|unique:usuarios,email',
            'role_id' => 'required|in:1,2',
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
        $patient = (int) $usuario->role_id === 3 ? Patient::forUser($usuario) : null;

        return view('users.profile', compact('usuario', 'patient'));
    }

    public function updateProfile(Request $request)
    {
        $usuario = Auth::user();
        $isPatient = (int) $usuario->role_id === 3;
        $patient = $isPatient ? Patient::forUser($usuario) : null;

        $rules = [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($patient) {
            $rules['telefono'] = 'required|string|max:20';
        }

        if (!$isPatient) {
            $rules['username'] = 'required|string|max:50|unique:usuarios,username,' . $usuario->id;
        }

        $validated = $request->validate($rules, [
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.confirmed' => 'Las contraseñas escritas no coinciden.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        ]);

        $data = [
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        if (!$isPatient) {
            $data['username'] = $validated['username'];
        }

        DB::transaction(function () use ($usuario, $data, $validated, $patient) {
            $usuario->update($data);

            if ($patient) {
                $patient->update([
                    'nombre' => $validated['nombre'],
                    'apellidos' => $validated['apellidos'],
                    'email' => $validated['email'],
                    'telefono' => $validated['telefono'],
                ]);
            }
        });

        return redirect()->route('profile.show')->with('success', 'Tus datos se actualizaron correctamente.');
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
            ->with('success', 'El usuario, su perfil de paciente, citas y tratamientos han sido eliminados correctamente del sistema.');
    }
}