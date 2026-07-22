<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Cita;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 

class CitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Si el usuario es un paciente (role_id == 3), filtramos solo sus citas usando paciente_id
        if ($user && $user->role_id == 3) {
            $paciente = Patient::where('user_id', $user->id)
                               ->orWhere('email', $user->email)
                               ->first();

            $citas = $paciente 
                ? Cita::with(['paciente', 'servicio'])
                    ->where('paciente_id', $paciente->id)
                    ->orderByRaw("CASE WHEN estado = '" . Cita::ESTADO_EN_ESPERA . "' THEN 0 ELSE 1 END ASC, fecha ASC, hora ASC")
                    ->get()
                : collect(); 
        } else {
            // Administrador y Asistente ven todas las citas del sistema
            $citas = Cita::with(['paciente', 'servicio'])
                ->orderByRaw("CASE WHEN estado = '" . Cita::ESTADO_EN_ESPERA . "' THEN 0 ELSE 1 END ASC, fecha ASC, hora ASC")
                ->get();
        }

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
        // 1. Lógica para manejar paciente nuevo o existente detectando si se llenó el nombre
        if ($request->filled('nombre') || $request->has('nuevo_paciente')) {
            $datosPaciente = $request->validate([
                'nombre'           => 'required|string|max:255',
                'apellidos'        => 'required|string|max:255',
                'email'            => 'required|email|max:255|unique:usuarios,email',
                'telefono'         => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date',
                'alergias'         => 'nullable|string',
                'enfermedades'     => 'nullable|string',
                'tratamientos'     => 'nullable|string',
            ], [
                'email.unique' => 'Este correo electrónico ya está registrado en el sistema.',
            ]);

            // A. Creamos su cuenta de acceso web
            $passwordTemporal = Str::random(8); 
            $usuario = User::create([
                'nombre'    => $datosPaciente['nombre'],
                'apellidos' => $datosPaciente['apellidos'],
                'username'  => Str::slug($datosPaciente['nombre'] . '.' . $datosPaciente['apellidos'] . '-' . rand(100, 999), ''),
                'email'     => $datosPaciente['email'],
                'password'  => Hash::make($passwordTemporal),
                'role_id'   => 3, // ID del rol Paciente
                'activo'    => 1,
            ]);

            // B. Vinculamos el registro del paciente con el ID del usuario creado
            $datosPaciente['user_id'] = $usuario->id; 
            
            $paciente = Patient::create($datosPaciente);
            $paciente_id = $paciente->id;

            // C. Guardamos temporalmente en sesión para mostrar las credenciales en la vista del paciente
            session([
                'temp_password' => $passwordTemporal,
                'temp_telefono' => $datosPaciente['telefono'],
                'temp_nombre'   => $datosPaciente['nombre'],            
                'temp_username' => $usuario->username,
            ]);

        } else {
            $request->validate(['paciente_id' => 'required|exists:pacientes,id']);
            $paciente_id = $request->paciente_id;

            // Limpiamos la sesión si se agendó cita a un paciente existente
            session()->forget(['temp_password', 'temp_telefono', 'temp_nombre', 'temp_username']);
        }

        // 2. Validación de cita
        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha'       => 'required|date',
            'hora'        => 'required',
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
            'fecha'       => $validated['fecha'],
            'hora'        => $validated['hora'],
            'estado'      => Cita::ESTADO_EN_ESPERA
        ]);

        // 💡 Si fue paciente nuevo, redirigimos a su vista de detalles (show) pasando las credenciales por sesión flash
        if ($request->filled('nombre') || $request->has('nuevo_paciente')) {
            return redirect()->route('patients.show', $paciente_id)
                             ->with([
                                 'success'       => 'Cita agendada exitosamente y cuenta creada para el paciente.',
                                 'temp_password' => $passwordTemporal,
                                 'temp_telefono' => $datosPaciente['telefono'],
                                 'temp_nombre'   => $datosPaciente['nombre'],
                                 'temp_username' => $usuario->username,
                             ]);
        }

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function show(string $id)
    {
        $cita = Cita::with(['paciente', 'servicio'])->findOrFail($id);
        return view('citas.show', compact('cita'));
    }

    public function edit(string $id)
    {
        $cita = Cita::findOrFail($id);
        $pacientes = Patient::all();
        $servicios = Servicio::all();
        return view('citas.edit', compact('cita', 'pacientes', 'servicios'));
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