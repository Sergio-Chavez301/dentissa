<?php

namespace App\Http\Controllers;

use App\Models\SolicitudCita;
use App\Models\Cita; 
use App\Models\Patient;
use App\Models\Servicio;
use App\Models\User;
use App\Models\Role;
use App\Models\OfficeSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SolicitudController extends Controller
{
    public function index()
    {
        $this->ensureStaffAccess();

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
        $this->ensureStaffAccess();

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
            'fecha_propuesta'  => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora_propuesta'   => 'required|date_format:H:i',
            'fecha_nacimiento' => 'required|date|before_or_equal:today',
            'motivo_consulta'  => 'nullable|string|max:2000',
        ]);

        $horariosDisponibles = Cita::getHorariosDisponibles($request->fecha_propuesta);
        if (!in_array($request->hora_propuesta, array_map(fn ($hora) => substr($hora, 0, 5), $horariosDisponibles), true)) {
            return back()->withInput()->withErrors([
                'hora_propuesta' => 'Ese horario ya no está disponible. Selecciona otra hora.',
            ])->with('open_request_modal', true);
        }

        $horaLimpia = $request->hora_propuesta;
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
        return redirect()->route('home')->with('success', 'Recibimos tu solicitud. El equipo confirmará tu horario por WhatsApp.');

    }

    public function update(Request $request, string $id)
    {
        $this->ensureStaffAccess();

        $solicitud = SolicitudCita::findOrFail($id);

        if ($solicitud->estado !== SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION) {
            return redirect()->route('solicitudes.index')->with('error', 'Acción no permitida.');
        }

        if ($request->has('confirmar')) {
            $request->validate([
                'servicio_id'      => 'required|exists:servicios,id',
                'fecha_nacimiento' => 'nullable|date|before_or_equal:today',
            ]);

            $resultado = DB::transaction(function () use ($id, $request) {
                $solicitud = SolicitudCita::whereKey($id)->lockForUpdate()->firstOrFail();

                if ($solicitud->estado !== SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION) {
                    return null;
                }

                $fechaHora = Carbon::parse($solicitud->fecha_hora_propuesta);
                $horariosDisponibles = Cita::getHorariosDisponibles($fechaHora->format('Y-m-d'), $solicitud->id);
                if (!in_array($fechaHora->format('H:i:s'), $horariosDisponibles, true)) {
                    throw ValidationException::withMessages([
                        'servicio_id' => 'El horario sugerido dejó de estar disponible. Contacta al prospecto para proponer otro.',
                    ]);
                }

                $roleId = Role::where('name', 'Paciente')->value('id');
                if (!$roleId) {
                    throw ValidationException::withMessages([
                        'servicio_id' => 'No existe el rol Paciente. Ejecuta los seeders de roles antes de confirmar.',
                    ]);
                }

                $passwordTemporal = Str::random(10);
                $usuario = User::create([
                    'nombre' => $solicitud->nombre,
                    'apellidos' => $solicitud->apellidos,
                    'username' => Str::slug($solicitud->nombre . '.' . $solicitud->apellidos, '') . Str::lower(Str::random(5)),
                    'email' => $solicitud->email,
                    'password' => Hash::make($passwordTemporal),
                    'role_id' => $roleId,
                    'activo' => true,
                ]);

                $paciente = Patient::create([
                    'nombre' => $solicitud->nombre,
                    'apellidos' => $solicitud->apellidos,
                    'email' => $solicitud->email,
                    'telefono' => $solicitud->telefono,
                    'fecha_nacimiento' => $request->input('fecha_nacimiento', $solicitud->fecha_nacimiento),
                    'user_id' => $usuario->id,
                ]);

                $cita = Cita::create([
                    'paciente_id' => $paciente->id,
                    'servicio_id' => $request->servicio_id,
                    'fecha' => $fechaHora->format('Y-m-d'),
                    'hora' => $fechaHora->format('H:i:s'),
                    'estado' => Cita::ESTADO_CONFIRMADA,
                ]);

                $solicitud->update(['estado' => SolicitudCita::ESTADO_CONFIRMADA]);

                return compact('solicitud', 'paciente', 'usuario', 'cita', 'passwordTemporal');
            });

            if (!$resultado) {
                return redirect()->route('solicitudes.index')->with('error', 'Esta solicitud ya fue procesada.');
            }

            ['solicitud' => $solicitud, 'paciente' => $paciente, 'usuario' => $usuario, 'cita' => $cita, 'passwordTemporal' => $passwordTemporal] = $resultado;
            
            session([
                'temp_password' => $passwordTemporal,
                'temp_telefono' => $paciente->telefono,
                'temp_nombre'   => $paciente->nombre,            
                'temp_username' => $usuario->username,
            ]);

            $officeSettings = OfficeSetting::current();
            $mensajeLineas = [
                'Hola ' . $paciente->nombre . ', tu cita en Dentissa quedó confirmada para el ' . Carbon::parse($cita->fecha)->format('d/m/Y') . ' a las ' . Carbon::parse($cita->hora)->format('H:i') . '.',
            ];
            if ($officeSettings->clinic_address) {
                $mensajeLineas[] = 'Dirección del consultorio: ' . $officeSettings->clinic_address;
            }
            if ($officeSettings->map_link) {
                $mensajeLineas[] = 'Mapa: ' . $officeSettings->map_link;
            }
            if ($officeSettings->whatsapp_phone) {
                $mensajeLineas[] = 'WhatsApp del consultorio: ' . $officeSettings->whatsapp_phone;
            }
            $mensajeLineas[] = 'Tu acceso al portal: usuario ' . $usuario->username . ', contraseña temporal ' . $passwordTemporal . '. Ingresa en ' . url('/login') . ' y cambia tu contraseña después de iniciar sesión.';
            $mensaje = implode("\n", $mensajeLineas);
            return redirect()->route('citas.index')->with([
                'success' => 'Cita agendada y cuenta creada para ' . $solicitud->nombre . '.',
                'whatsapp_url' => $this->whatsappUrl($paciente->telefono, $mensaje),
                'whatsapp_label' => 'Enviar confirmación por WhatsApp',
            ]);
        }

        $solicitud->update(['estado' => SolicitudCita::ESTADO_CANCELADA]);

        $mensaje = 'Hola ' . $solicitud->nombre . ', gracias por contactar a Dentissa. No pudimos confirmar el horario solicitado. Responde a este mensaje para buscar otra opción.';
        return redirect()->route('solicitudes.index')->with([
            'success' => 'Solicitud de ' . $solicitud->nombre . ' rechazada.',
            'whatsapp_url' => $this->whatsappUrl($solicitud->telefono, $mensaje),
            'whatsapp_label' => 'Avisar al prospecto por WhatsApp',
        ]);
    }

    private function whatsappUrl(string $telefono, string $mensaje): string
    {
        $telefono = preg_replace('/\D+/', '', $telefono);

        return 'https://wa.me/' . $telefono . '?text=' . urlencode($mensaje);
    }

    private function ensureStaffAccess(): void
    {
        abort_unless((int) auth()->user()->role_id !== 3, 403);
    }
}