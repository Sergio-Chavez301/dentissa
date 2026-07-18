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
    // Restored the index method
    public function index()
    {
        $solicitudes = SolicitudCita::orderBy('fecha_hora_propuesta', 'asc')->get();
        return view('solicitudes.index', compact('solicitudes'));
    }

    public function show(string $id)
    {
        $solicitud = SolicitudCita::findOrFail($id);
        $pacientes = Patient::all();
        $servicios = Servicio::all();
        return view('solicitudes.show', compact('solicitud', 'pacientes', 'servicios'));
    }

    public function update(Request $request, string $id)
    {
        $solicitud = SolicitudCita::findOrFail($id);

        if ($request->has('confirmar')) {
            // Si el usuario no seleccionó un paciente, lo creamos usando los datos de la solicitud
            if (!$request->paciente_id) {
                $paciente = Patient::create([
                    'nombre'           => $solicitud->nombre,
                    'apellidos'        => $solicitud->apellidos,
                    'email'            => $solicitud->email,
                    'telefono'         => $solicitud->telefono,
                    'fecha_nacimiento' => $solicitud->fecha_nacimiento, 
                ]);
                $pacienteId = $paciente->id;
            } else {
                $pacienteId = $request->paciente_id;
            }

            Cita::create([
                'paciente_id' => $pacienteId,
                'servicio_id' => $request->servicio_id,
                'fecha'       => Carbon::parse($solicitud->fecha_hora_propuesta)->format('Y-m-d'),
                'hora'        => Carbon::parse($solicitud->fecha_hora_propuesta)->format('H:i:s'),
                'estado'      => 'confirmada',
            ]);

            $solicitud->update(['estado' => 'confirmada']);
            return redirect()->route('solicitudes.index')->with('success', 'Cita agendada correctamente.');
        }

        // Si es rechazo
        $solicitud->update(['estado' => 'rechazada']);
        return redirect()->route('solicitudes.index')->with('success', 'Solicitud rechazada.');
    }
}