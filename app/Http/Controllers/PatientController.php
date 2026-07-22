<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Cita;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Muestra el panel exclusivo para el paciente autenticado.
     */
    public function dashboard()
    {
        $user = Auth::user();

        $patient = Patient::with(['citas.servicio', 'casosClinicos'])
            ->where('user_id', $user->id)
            ->first();

        if (!$patient) {
            return view('patients.sin-perfil');
        }

        // Apunta directamente a resources/views/dashboard/paciente.blade.php
        return view('dashboard.paciente', compact('patient'));
    }

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
            'email' => 'nullable|email|max:255|unique:usuarios,email',
            'fecha_nacimiento' => 'required|date',
            'alergias' => 'nullable|string',
            'enfermedades' => 'nullable|string',
            'tratamientos' => 'nullable|string',
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado en el sistema de usuarios.'
        ]);

        // 1. Generar un username único basado en su nombre y un número aleatorio
        $usernameBase = Str::slug($validated['nombre'] . '.' . $validated['apellidos'], '');
        $username = $usernameBase . rand(100, 999);
        while (User::where('username', $username)->exists()) {
            $username = $usernameBase . rand(100, 999);
        }

        // 2. Generar una contraseña temporal aleatoria de 8 caracteres
        $passwordTemporal = Str::random(8);

        // 3. Crear la cuenta de usuario vinculada
        $usuario = User::create([
            'nombre'    => $validated['nombre'],
            'apellidos' => $validated['apellidos'],
            'username'  => $username,
            'email'     => $validated['email'] ?? 'paciente_' . time() . '@dentissa.com',
            'password'  => Hash::make($passwordTemporal),
            'role_id'   => 3, // Rol Paciente
            'activo'    => 1,
        ]);

        // 4. Asignar el user_id generado al array de validación del paciente
        $validated['user_id'] = $usuario->id;

        // 5. Crear el paciente
        $patient = Patient::create($validated);

        // 6.Guardar los datos en la sesión para que aparezca la alerta de WhatsApp al crearlo
        session([
            'temp_password' => $passwordTemporal,
            'temp_telefono' => $patient->telefono,
            'temp_nombre'   => $patient->nombre,            
            'temp_username' => $usuario->username,
        ]);

        return redirect()->route('patients.show', $patient->id)->with('success', 'Paciente y su cuenta de usuario creados exitosamente.');
    }

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
            'email' => 'nullable|email|max:255',
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

        // 1. Si el paciente tiene una cuenta de usuario asociada, la eliminamos
        if ($patient->user_id) {
            User::where('id', $patient->user_id)->delete();
        }

        // 2. Eliminamos todos los casos clínicos asociados para evitar registros huérfanos
        if (method_exists($patient, 'casosClinicos')) {
            $patient->casosClinicos()->delete();
        }

        // 3. Eliminamos todas las citas asociadas para evitar registros huérfanos
        Cita::where('paciente_id', $patient->id)->delete();

        // 4. Eliminamos al paciente
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente, su cuenta de usuario, citas y casos clínicos fueron eliminados correctamente.');
    }

    /**
     * Genera o regenera la contraseña temporal y prepara los datos para enviar por WhatsApp.
     */
    public function enviarCredencialesWhatsApp(string $id)
    {
        $patient = Patient::findOrFail($id);

        // Si el paciente no tiene un usuario web asignado, se lo creamos
        if (!$patient->user_id) {
            $passwordTemporal = Str::random(8);
            $usuario = User::create([
                'nombre'    => $patient->nombre,
                'apellidos' => $patient->apellidos,
                'username'  => Str::slug($patient->nombre . '.' . $patient->apellidos . '-' . rand(100, 999), ''),
                'email'     => $patient->email ?? 'paciente_' . $patient->id . '@dentissa.com',
                'password'  => Hash::make($passwordTemporal),
                'role_id'   => 3, // Rol Paciente
                'activo'    => 1,
            ]);

            $patient->update(['user_id' => $usuario->id]);
        } else {
            // Si ya tiene usuario, le generamos una nueva contraseña temporal y se la actualizamos
            $usuario = User::findOrFail($patient->user_id);
            $passwordTemporal = Str::random(8);
            $usuario->update([
                'password' => Hash::make($passwordTemporal)
            ]);
        }

        // Guardamos en sesión los datos temporales para activar la alerta verde de WhatsApp en la vista
        session([
            'temp_password' => $passwordTemporal,
            'temp_telefono' => $patient->telefono,
            'temp_nombre'   => $patient->nombre,            
            'temp_username' => $usuario->username,
        ]);

        return redirect()->route('patients.show', $patient->id)->with('success', 'Credenciales generadas correctamente.');
    }
}