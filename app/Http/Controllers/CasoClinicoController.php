<?php

namespace App\Http\Controllers;

use App\Models\CasoClinico;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CasoClinicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Si el usuario es un paciente (role_id == 3), filtramos solo sus casos clínicos
        if ($user && $user->role_id == 3) {
            $paciente = Patient::where('user_id', $user->id)
                               ->orWhere('email', $user->email)
                               ->first();

            $casos = $paciente 
                ? CasoClinico::with('paciente')
                    ->where('paciente_id', $paciente->id)
                    ->get()
                : collect(); // Si no tiene ficha asociada, retorna colección vacía
        } else {
            // Administrador y Asistente ven todos los casos clínicos del sistema
            $casos = CasoClinico::with('paciente')->get();
        }

        return view('casos.index', compact('casos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pacientes = Patient::all();
        return view('casos.create', compact('pacientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validamos de forma condicional si viene un paciente nuevo o uno existente
        $rules = [
            'tratamiento_base' => 'required|string|max:255',
            'progreso'         => 'required|integer|min:0|max:100',
            'estado'           => 'nullable|string',
        ];

        if ($request->has('nuevo_paciente')) {
            $rules['nombre']           = 'required|string|max:255';
            $rules['apellidos']        = 'required|string|max:255';
            $rules['email']            = 'nullable|email|max:255|unique:pacientes,email';
            $rules['telefono']         = 'nullable|string|max:20';
            $rules['fecha_nacimiento'] = 'nullable|date';
            $rules['alergias']         = 'nullable|string';
            $rules['enfermedades']     = 'nullable|string';
            $rules['tratamientos']     = 'nullable|string';
        } else {
            $rules['paciente_id'] = 'required|exists:pacientes,id';
        }

        $validated = $request->validate($rules);

        $passwordTemporal = null;
        $usernameCreado = null;

        // 2. Si se marcó la opción de nuevo paciente, lo creamos incluyendo su correo
        if ($request->has('nuevo_paciente')) {
            $patient = Patient::create([
                'nombre'           => $validated['nombre'],
                'apellidos'        => $validated['apellidos'],
                'email'            => $validated['email'] ?? null,
                'telefono'         => $validated['telefono'] ?? null,
                'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
                'alergias'         => $validated['alergias'] ?? null,
                'enfermedades'     => $validated['enfermedades'] ?? null,
                'tratamientos'     => $validated['tratamientos'] ?? null,
            ]);
            
            $pacienteId = $patient->id;
        } else {
            $pacienteId = $validated['paciente_id'];
            $patient = Patient::findOrFail($pacienteId);
        }

        // 3. Creamos el caso clínico asociado al paciente
        $caso = CasoClinico::create([
            'paciente_id'      => $pacienteId,
            'tratamiento_base' => $validated['tratamiento_base'],
            'progreso'         => $validated['progreso'],
            'estado'           => $request->input('estado', 'activo'),
        ]);

        // 4. Gestión automática de cuenta web para el paciente (si no la tiene)
        if (!$patient->user_id) {
            $usernameBase = Str::slug($patient->nombre . '.' . $patient->apellidos, '');
            $username = $usernameBase . rand(100, 999);
            while (User::where('username', $username)->exists()) {
                $username = $usernameBase . rand(100, 999);
            }

            $passwordTemporal = Str::random(8);

            // Se asigna su correo real (o el comodín si se dejó totalmente en blanco)
            $usuario = User::create([
                'nombre'    => $patient->nombre,
                'apellidos' => $patient->apellidos,
                'username'  => $username,
                'email'     => $patient->email ?? 'paciente_' . $patient->id . '@dentissa.com',
                'password'  => Hash::make($passwordTemporal),
                'role_id'   => 3, 
                'activo'    => 1,
            ]);

            $patient->update(['user_id' => $usuario->id]);
            $usernameCreado = $usuario->username;
        } else {
            if ($patient->user) {
                $usernameCreado = $patient->user->username;
            }
        }

        // 5. Redirección con éxito
        $redirect = redirect()->route('casos.index')
                              ->with('success', 'Caso clínico registrado exitosamente.');

        if ($passwordTemporal) {
            $redirect->with([
                'temp_password' => $passwordTemporal,
                'temp_telefono' => $patient->telefono,
                'temp_nombre'   => $patient->nombre,
                'temp_username' => $usernameCreado,
            ]);
        }

        return $redirect;
    }

    public function show(string $id)
    {
        $caso = CasoClinico::with('paciente')->findOrFail($id);
        return view('casos.show', compact('caso'));
    }

    public function edit(string $id)
    {
        $caso = CasoClinico::findOrFail($id);
        $patients = Patient::all();
        return view('casos.edit', compact('caso', 'patients'));
    }

    public function update(Request $request, string $id)
    {
        $caso = CasoClinico::findOrFail($id);

        $validated = $request->validate([
            'paciente_id'      => 'required|exists:pacientes,id',
            'tratamiento_base' => 'required|string|max:255',
            'descripcion'      => 'nullable|string',
            'progreso'         => 'required|integer|min:0|max:100',
            'estado'           => 'required|in:activo,pausado,completado',
        ]);

        $caso->update($validated);

        return redirect()->route('casos.index')->with('success', 'Caso clínico actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $caso = CasoClinico::findOrFail($id);
        $caso->delete();

        return redirect()->route('casos.index')->with('success', 'Caso clínico eliminado correctamente.');
    }
}