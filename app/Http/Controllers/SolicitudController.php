<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCita;
use App\Models\Cita; 
use App\Models\Patient;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Carbon\Carbon;

class solicitudController extends Controller
{
    public function index()
    {
        // Solo mostramos solicitudes que están pendientes (esperando confirmación)
        $solicitudes = SolicitudCita::where('estado', SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION)
                                    ->orderBy('fecha_hora_propuesta', 'asc')
                                    ->get();
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function show(string $id)
    {
        $solicitud = SolicitudCita::findOrFail($id);
        
        // Si la solicitud ya no está pendiente, redirigir al listado para evitar ediciones
        if ($solicitud->estado !== SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION) {
            return redirect()->route('solicitudes.index')->with('error', 'Esta solicitud ya ha sido procesada anteriormente.');
        }

        $pacientes = Patient::all();
        $servicios = Servicio::all();
        return view('solicitudes.show', compact('solicitud', 'pacientes', 'servicios'));
    }

public function update(Request $request, string $id)
{
    $solicitud = SolicitudCita::findOrFail($id);

    // 1. Seguridad: Verificar estado antes de cualquier acción
    if ($solicitud->estado !== SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION) {
        return redirect()->route('solicitudes.index')->with('error', 'Acción no permitida.');
    }

    if ($request->has('confirmar')) {
        // 2. Validación obligatoria
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            // Opcional: validar fecha_nacimiento si es parte del form
            'fecha_nacimiento' => 'nullable|date', 
        ]);

        // 3. Crear o asignar paciente
        $pacienteId = $request->paciente_id;
        
        if (!$pacienteId) {
            $paciente = Patient::create([
                'nombre'           => $solicitud->nombre,
                'apellidos'        => $solicitud->apellidos,
                'email'            => $solicitud->email,
                'telefono'         => $solicitud->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
            ]);
            $pacienteId = $paciente->id;
        }

        // 4. Crear la cita
        Cita::create([
            'paciente_id' => $pacienteId,
            'servicio_id' => $request->servicio_id,
            'fecha'       => Carbon::parse($solicitud->fecha_hora_propuesta)->format('Y-m-d'),
            'hora'        => Carbon::parse($solicitud->fecha_hora_propuesta)->format('H:i:s'),
            'estado'      => Cita::ESTADO_EN_ESPERA, 
        ]);

        // 5. Finalizar solicitud
        $solicitud->update(['estado' => SolicitudCita::ESTADO_CONFIRMADA]);
        
        return redirect()->route('solicitudes.index')->with('success', 'Cita agendada correctamente para ' . $solicitud->nombre . '.');
    }

    // Rechazo
    $solicitud->update(['estado' => SolicitudCita::ESTADO_CANCELADA]);
    return redirect()->route('solicitudes.index')->with('success', 'Solicitud de ' . $solicitud->nombre . ' rechazada.');
}
}