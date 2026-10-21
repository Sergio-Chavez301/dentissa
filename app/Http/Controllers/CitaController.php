<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 

class CitaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

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
            $citas = Cita::with(['paciente', 'servicio'])
                ->orderByRaw("CASE WHEN estado = '" . Cita::ESTADO_EN_ESPERA . "' THEN 0 ELSE 1 END ASC, fecha ASC, hora ASC")
                ->get();
        }

        return view('citas.index', compact('citas'));
    }

    public function create(Request $request)
    {
        $this->ensureStaffAccess();

        $pacientes = Patient::all();
        $servicios = Servicio::all();
        $casos = CasoClinico::with('paciente')->get();
        $pacienteSeleccionado = $request->query('paciente_id');
        $casoSeleccionado = $request->query('caso_clinico_id');

        return view('citas.create', compact('pacientes', 'servicios', 'casos', 'pacienteSeleccionado', 'casoSeleccionado'));
    }

    public function createForCase(CasoClinico $caso)
    {
        $this->ensureStaffAccess();

        $caso->load('paciente');
        $servicios = Servicio::all();

        return view('citas.create', compact('caso', 'servicios'));
    }

    public function verificarDisponibilidad(Request $request)
    {
        $fecha = $request->query('fecha');
        if (!$fecha) return response()->json(['horarios' => []]);

        $request->validate([
            'fecha' => 'date_format:Y-m-d|after_or_equal:today',
        ]);

        $horarios = Cita::getHorariosDisponibles($fecha);
        return response()->json(['horarios' => array_values($horarios)]);
    }

    public function store(Request $request)
    {
        $this->ensureStaffAccess();

        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s',
            'caso_clinico_id' => [
                'nullable',
                Rule::exists('casos_clinicos', 'id')
                    ->where('paciente_id', $request->input('paciente_id')),
                Rule::prohibitedIf($request->has('nuevo_paciente') || $request->filled('nombre')),
            ],
        ]);

        if (!in_array($validated['hora'], Cita::getHorariosDisponibles($validated['fecha']), true)) {
            return back()->withInput()->withErrors([
                'hora' => 'Lo sentimos, este horario ya no está disponible.',
            ]);
        }

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

            $passwordTemporal = Str::random(8); 
            $usuario = User::create([
                'nombre'    => $datosPaciente['nombre'],
                'apellidos' => $datosPaciente['apellidos'],
                'username'  => Str::slug($datosPaciente['nombre'] . '.' . $datosPaciente['apellidos'] . '-' . rand(100, 999), ''),
                'email'     => $datosPaciente['email'],
                'password'  => Hash::make($passwordTemporal),
                'role_id'   => 3,
                'activo'    => 1,
            ]);

            $datosPaciente['user_id'] = $usuario->id; 
            
            $paciente = Patient::create($datosPaciente);
            $paciente_id = $paciente->id;

            session([
                'temp_password' => $passwordTemporal,
                'temp_telefono' => $datosPaciente['telefono'],
                'temp_nombre'   => $datosPaciente['nombre'],            
                'temp_username' => $usuario->username,
            ]);

        } else {
            $request->validate(['paciente_id' => 'required|exists:pacientes,id']);
            $paciente_id = $request->paciente_id;

            session()->forget(['temp_password', 'temp_telefono', 'temp_nombre', 'temp_username']);
        }

        $cita = Cita::create([
            'paciente_id' => $paciente_id,
            'caso_clinico_id' => $validated['caso_clinico_id'] ?? null,
            'servicio_id' => $validated['servicio_id'],
            'fecha'       => $validated['fecha'],
            'hora'        => $validated['hora'],
            'estado'      => Cita::ESTADO_EN_ESPERA
        ]);

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

        if ($cita->caso_clinico_id) {
            return redirect()->route('casos.edit', $cita->caso_clinico_id)
                ->with('success', 'Cita registrada en el tratamiento.');
        }

        return redirect()->route('citas.index')->with('success', 'Cita agendada exitosamente.');
    }

    public function storeForCase(Request $request, CasoClinico $caso)
    {
        $this->ensureStaffAccess();

        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => 'required|date_format:H:i:s',
        ]);

        if (!in_array($validated['hora'], Cita::getHorariosDisponibles($validated['fecha']), true)) {
            return back()->withInput()->withErrors([
                'hora' => 'Lo sentimos, este horario ya no está disponible.',
            ]);
        }

        Cita::create([
            'paciente_id' => $caso->paciente_id,
            'caso_clinico_id' => $caso->id,
            'servicio_id' => $validated['servicio_id'],
            'fecha' => $validated['fecha'],
            'hora' => $validated['hora'],
            'estado' => Cita::ESTADO_EN_ESPERA,
        ]);

        return redirect()->route('casos.edit', $caso)
            ->with('success', 'Cita registrada en este tratamiento.');
    }

    public function show(string $id)
    {
        $this->ensureStaffAccess();

        $cita = Cita::with(['paciente', 'servicio'])->findOrFail($id);
        return view('citas.show', compact('cita'));
    }

    public function edit(string $id)
    {
        $this->ensureStaffAccess();

        $cita = Cita::findOrFail($id);
        $pacientes = Patient::all();
        $servicios = Servicio::all();
        $casos = CasoClinico::where('paciente_id', $cita->paciente_id)->get();
        return view('citas.edit', compact('cita', 'pacientes', 'servicios', 'casos'));
    }

    public function update(Request $request, string $id)
    {
        $this->ensureStaffAccess();

        $cita = Cita::findOrFail($id);
        
        $request->validate([
            'fecha'  => 'required|date',
            'hora'   => 'required',
            'estado' => 'required|in:en_espera,confirmada,realizada,cancelada,no_presento',
            'caso_clinico_id' => [
                'nullable',
                Rule::exists('casos_clinicos', 'id')
                    ->where('paciente_id', $cita->paciente_id),
            ],
        ]);

        // Verificamos disponibilidad SOLO si la fecha o la hora han cambiado
        if ($request->fecha !== $cita->fecha || $request->hora !== $cita->hora) {
            $horariosDisponibles = Cita::getHorariosDisponibles($request->fecha);
            
            if (!in_array($request->hora, $horariosDisponibles)) {
                return back()->withInput()->with('error', 'El horario seleccionado no está disponible.');
            }
        }

        $cita->update([
            'caso_clinico_id' => $request->input('caso_clinico_id') ?: null,
            'fecha'  => $request->fecha,
            'hora'   => $request->hora,
            'estado' => $request->estado,
        ]);

        return redirect()->route('citas.index')->with('success', 'Cita actualizada correctamente.');
    }

    public function updateStatus(Request $request, Cita $cita)
    {
        $this->ensureStaffAccess();

        $allowedTransitions = match ($cita->estado) {
            Cita::ESTADO_EN_ESPERA => [Cita::ESTADO_CONFIRMADA, Cita::ESTADO_CANCELADA],
            Cita::ESTADO_CONFIRMADA => [Cita::ESTADO_REALIZADA, Cita::ESTADO_NO_PRESENTO],
            default => [],
        };

        $validated = $request->validate([
            'estado' => ['required', Rule::in($allowedTransitions)],
        ]);

        $cita->update(['estado' => $validated['estado']]);

        $messages = [
            Cita::ESTADO_CONFIRMADA => 'La cita fue confirmada.',
            Cita::ESTADO_CANCELADA => 'La cita fue rechazada y cancelada.',
            Cita::ESTADO_REALIZADA => 'La cita fue marcada como realizada.',
            Cita::ESTADO_NO_PRESENTO => 'La cita fue marcada como inasistencia.',
        ];

        return redirect()->route('citas.index')->with('success', $messages[$validated['estado']]);
    }

    private function ensureStaffAccess(): void
    {
        abort_unless((int) Auth::user()->role_id !== 3, 403);
    }
}