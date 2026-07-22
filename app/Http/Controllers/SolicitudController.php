<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCita;
use App\Models\Cita; 
use App\Models\Patient;
use App\Models\Servicio;
use App\Models\User; // <-- Asegúrate de importar el modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Importante para la contraseña
use Illuminate\Support\Str; // <-- Importante para generar la clave aleatoria
use Carbon\Carbon;

class SolicitudController extends Controller
{
    public function index()
    {
        try {
            $solicitudes = SolicitudCita::where('estado', SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION)
                                        ->orderBy('fecha_hora_propuesta', 'asc')
                                        ->get();
                                        
            return view('solicitudes.index', compact('solicitudes'));
        } catch (\Exception $e) {
            return "Error en la base de datos: " . $e->getMessage();
        }
    }

    public function show(string $id)
    {
        $solicitud = SolicitudCita::findOrFail($id);
        
        if ($solicitud->estado !== SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION) {
            return redirect()->route('solicitudes.index')->with('error', 'Esta solicitud ya ha sido procesada anteriormente.');
        }

        $servicios = Servicio::all();
        
        return view('solicitudes.show', compact('solicitud', 'servicios'));
    }

    public function storePublic(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:255',
            'apellidos'        => 'required|string|max:255',
            'telefono'         => 'required|string|max:20',
            'email'            => 'nullable|email|max:255',
            'fecha_propuesta'  => 'required|date',
            'hora_propuesta'   => 'required',
            'fecha_nacimiento' => 'required|date',
            'motivo_consulta'  => 'nullable|string',
        ]);

        $horaLimpia = substr($request->hora_propuesta, 0, 5);
        $fechaHoraCompleta = $request->fecha_propuesta . ' ' . $horaLimpia . ':00';

        SolicitudCita::create([
            'nombre'               => $request->nombre,
            'apellidos'            => $request->apellidos,
            'telefono'             => $request->telefono,
            'email'                => $request->email,
            'fecha_hora_propuesta' => $fechaHoraCompleta,
            'fecha_nacimiento'     => $request->fecha_nacimiento,
            'motivo_consulta'      => $request->motivo_consulta,
            'estado'               => SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION,
        ]);

        return back()->with('success', '¡Tu solicitud de cita ha sido enviada con éxito! Nos pondremos en contacto o la confirmaremos pronto.');
    }

    public function update(Request $request, string $id)
    {
        $solicitud = SolicitudCita::findOrFail($id);

        if ($solicitud->estado !== SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION) {
            return redirect()->route('solicitudes.index')->with('error', 'Acción no permitida.');
        }

        if ($request->has('confirmar')) {
            $request->validate([
                'servicio_id'      => 'required|exists:servicios,id',
                'fecha_nacimiento' => 'nullable|date', 
            ]);

            // 1. Creamos la cuenta de acceso web (usuario)
            $passwordTemporal = Str::random(8); 
            $usuario = User::create([
                'nombre'    => $solicitud->nombre,
                'apellidos' => $solicitud->apellidos,
                'username'  => Str::slug($solicitud->nombre . '.' . $solicitud->apellidos . '-' . rand(100, 999), ''),
                'email'     => $solicitud->email,
                'password'  => Hash::make($passwordTemporal),
                'role_id'   => 3, // Rol Paciente
                'activo'    => 1,
            ]);

            // 2. Creamos el paciente vinculando el user_id recién creado
            $paciente = Patient::create([
                'nombre'           => $solicitud->nombre,
                'apellidos'        => $solicitud->apellidos,
                'email'            => $solicitud->email,
                'telefono'         => $solicitud->telefono,
                'fecha_nacimiento' => $request->input('fecha_nacimiento', $solicitud->fecha_nacimiento),
                'user_id'          => $usuario->id, // <-- Enlace clave
            ]);

            // 3. Creamos la cita oficial
            Cita::create([
                'paciente_id' => $paciente->id,
                'servicio_id' => $request->servicio_id,
                'fecha'       => Carbon::parse($solicitud->fecha_hora_propuesta)->format('Y-m-d'),
                'hora'        => Carbon::parse($solicitud->fecha_hora_propuesta)->format('H:i:s'),
                'estado'      => Cita::ESTADO_EN_ESPERA, 
            ]);

            // 4. Cambiamos el estado de la solicitud
            $solicitud->update(['estado' => SolicitudCita::ESTADO_CONFIRMADA]);
            
            // 5. Guardamos en sesión para activar el botón de WhatsApp en la vista
            session([
                'temp_password' => $passwordTemporal,
                'temp_telefono' => $paciente->telefono,
                'temp_nombre'   => $paciente->nombre,            
                'temp_username' => $usuario->username,
            ]);

            return redirect()->route('citas.index')->with('success', 'Cita agendada correctamente y cuenta de acceso creada para ' . $solicitud->nombre . '.');
        }

        $solicitud->update(['estado' => SolicitudCita::ESTADO_CANCELADA]);
        
        return redirect()->route('citas.index')->with('success', 'Solicitud de ' . $solicitud->nombre . ' rechazada.');
    }
}