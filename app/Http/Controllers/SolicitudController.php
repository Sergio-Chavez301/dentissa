<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCita;
use App\Models\Cita; 
use App\Models\Patient;
use App\Models\Servicio;
use Illuminate\Http\Request;
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

        // Cortamos la hora a 5 caracteres (HH:MM) para prevenir segundos duplicados
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

            $paciente = Patient::create([
                'nombre'           => $solicitud->nombre,
                'apellidos'        => $solicitud->apellidos,
                'email'            => $solicitud->email,
                'telefono'         => $solicitud->telefono,
                'fecha_nacimiento' => $request->input('fecha_nacimiento', $solicitud->fecha_nacimiento),
            ]);

            Cita::create([
                'paciente_id' => $paciente->id,
                'servicio_id' => $request->servicio_id,
                'fecha'       => Carbon::parse($solicitud->fecha_hora_propuesta)->format('Y-m-d'),
                'hora'        => Carbon::parse($solicitud->fecha_hora_propuesta)->format('H:i:s'),
                'estado'      => Cita::ESTADO_EN_ESPERA, 
            ]);

            $solicitud->update(['estado' => SolicitudCita::ESTADO_CONFIRMADA]);
            
            return redirect()->route('solicitudes.index')->with('success', 'Cita agendada correctamente y paciente registrado para ' . $solicitud->nombre . '.');
        }

        $solicitud->update(['estado' => SolicitudCita::ESTADO_CANCELADA]);
        
        return redirect()->route('solicitudes.index')->with('success', 'Solicitud de ' . $solicitud->nombre . ' rechazada.');
    }
}