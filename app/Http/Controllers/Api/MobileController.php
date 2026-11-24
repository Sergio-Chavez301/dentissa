<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Oferta;
use App\Models\OfficeSetting;
use App\Models\Patient;
use App\Models\PushDevice;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MobileController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $identifier = trim($credentials['username']);
        $user = User::where('username', $identifier)->first();
        if (!$user && filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
        }

        if (
            !$user
            || !$user->activo
            || !in_array((int) $user->role_id, [1, 2, 3], true)
            || !Hash::check($credentials['password'], $user->password)
        ) {
            return response()->json([
                'message' => 'Usuario o contraseña incorrectos.',
            ], 401)->header('Cache-Control', 'no-store');
        }

        if ((int) $user->role_id === 3 && !Patient::where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'La cuenta no tiene un perfil de paciente asociado. Contacta al consultorio.',
            ], 403)->header('Cache-Control', 'no-store');
        }

        $token = $user->createToken('dentissa-android')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => [
                'name' => trim($user->nombre . ' ' . $user->apellidos),
                'role_id' => (int) $user->role_id,
            ],
        ])->header('Cache-Control', 'no-store');
    }

    public function supportContact(): JsonResponse
    {
        $officeSettings = OfficeSetting::current();

        return response()->json([
            'whatsapp_digits' => $officeSettings->whatsapp_digits,
            'clinic_name' => $officeSettings->clinic_name,
        ])->header('Cache-Control', 'no-store');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada.',
        ])->header('Cache-Control', 'no-store');
    }

    public function registerPushDevice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:512'],
            'platform' => ['required', Rule::in(['android'])],
        ]);

        PushDevice::updateOrCreate(
            ['token' => $validated['token']],
            [
                'user_id' => $request->user()->id,
                'platform' => $validated['platform'],
            ]
        );

        return response()->json([
            'message' => 'Este dispositivo recibirá avisos de Dentissa.',
        ], 201)->header('Cache-Control', 'no-store');
    }

    public function removePushDevice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:512'],
        ]);

        $request->user()->pushDevices()->where('token', $validated['token'])->delete();

        return response()->json([
            'message' => 'Se desactivaron los avisos en este dispositivo.',
        ])->header('Cache-Control', 'no-store');
    }

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        if ((int) $user->role_id !== 3) {
            return $this->staffDashboard($user);
        }

        $patient = $this->patientFor($user);

        $appointments = Cita::with('servicio:id,nombre')
            ->with('casoClinico:id,tratamiento_base')
            ->where('paciente_id', $patient->id)
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->limit(50)
            ->get()
            ->map(fn (Cita $appointment) => [
                'id' => $appointment->id,
                'date' => $appointment->fecha,
                'time' => substr($appointment->hora, 0, 5),
                'status' => $appointment->estado,
                'service' => $appointment->tratamiento_realizado ?: ($appointment->servicio?->nombre ?? 'Consulta general'),
                'treatment' => $appointment->casoClinico?->tratamiento_base,
            ]);

        $appointmentCounts = Cita::where('paciente_id', $patient->id)
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $treatments = CasoClinico::with([
            'citas' => fn ($query) => $query
                ->reorder()
                ->with('servicio:id,nombre')
                ->orderByDesc('fecha')
                ->orderByDesc('hora'),
        ])
            ->where('paciente_id', $patient->id)
            ->get()
            ->map(fn (CasoClinico $treatment) => [
                'id' => $treatment->id,
                'name' => $treatment->tratamiento_base ?: 'Tratamiento dental',
                'progress' => min(100, max(0, (int) $treatment->progreso)),
                'status' => $treatment->estado,
                'completed_appointments' => $treatment->citas->map(fn (Cita $appointment) => [
                    'id' => $appointment->id,
                    'service' => $appointment->tratamiento_realizado ?: ($appointment->servicio?->nombre ?? 'Consulta general'),
                    'date' => $appointment->fecha,
                    'time' => substr($appointment->hora, 0, 5),
                    'status' => $appointment->estado,
                ])->values(),
            ]);

        $officeSettings = OfficeSetting::current();
        $offers = Oferta::where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->limit(3)
            ->get(['titulo', 'tag', 'descripcion', 'precio'])
            ->map(fn (Oferta $offer) => [
                'title' => $offer->titulo,
                'tag' => $offer->tag,
                'description' => $offer->descripcion,
                'price' => $offer->precio,
                'whatsapp_digits' => preg_replace('/\D+/', '', $officeSettings->whatsapp_phone ?? ''),
                'clinic_name' => $officeSettings->clinic_name,
            ]);

        return response()->json([
            'role_id' => 3,
            'patient' => [
                'name' => trim($patient->nombre . ' ' . $patient->apellidos),
                'username' => $user->username,
                'email' => $patient->email ?? $user->email,
                'phone' => $patient->telefono,
                'birth_date' => $patient->fecha_nacimiento,
                'allergies' => $patient->alergias,
                'conditions' => $patient->enfermedades,
                'current_treatments' => $patient->tratamientos,
            ],
            'appointment_counts' => [
                'en_espera' => (int) $appointmentCounts->get(Cita::ESTADO_EN_ESPERA, 0),
                'confirmada' => (int) $appointmentCounts->get(Cita::ESTADO_CONFIRMADA, 0),
                'cancelada' => (int) $appointmentCounts->get(Cita::ESTADO_CANCELADA, 0),
                'no_presento' => (int) $appointmentCounts->get(Cita::ESTADO_NO_PRESENTO, 0),
                'realizada' => (int) $appointmentCounts->get(Cita::ESTADO_REALIZADA, 0),
            ],
            'appointments' => $appointments,
            'treatments' => $treatments,
            'offers' => $offers,
            'office_location' => [
                'name' => $officeSettings->clinic_name,
                'address' => $officeSettings->clinic_address,
                'map_link' => $officeSettings->map_link,
                'map_embed_link' => $officeSettings->map_embed_link,
            ],
            'services' => Servicio::query()
                ->orderBy('nombre')
                ->get(['id', 'nombre'])
                ->map(fn (Servicio $service) => [
                    'id' => $service->id,
                    'name' => $service->nombre,
                ]),
        ])->header('Cache-Control', 'no-store');
    }

    public function availability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        $slots = array_map(
            fn (string $time) => substr($time, 0, 5),
            Cita::getHorariosDisponibles($validated['date'])
        );

        return response()->json([
            'slots' => $slots,
        ])->header('Cache-Control', 'no-store');
    }

    public function storeAppointment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'service_id' => ['required', 'integer', 'exists:servicios,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $appointment = DB::transaction(function () use ($validated, $request) {
            $time = $validated['time'] . ':00';
            $availableSlots = Cita::getHorariosDisponibles($validated['date']);

            if (!in_array($time, $availableSlots, true)) {
                throw ValidationException::withMessages([
                    'time' => 'Ese horario ya no está disponible. Actualiza los horarios y elige otro.',
                ]);
            }

            return Cita::create([
                'paciente_id' => $this->patientFor($request->user())->id,
                'servicio_id' => $validated['service_id'],
                'fecha' => $validated['date'],
                'hora' => $time,
                'estado' => Cita::ESTADO_EN_ESPERA,
                'origen' => Cita::ORIGEN_APP,
            ])->load('servicio:id,nombre');
        });

        return response()->json([
            'message' => 'La solicitud se envió al consultorio. La cita queda pendiente de confirmación.',
            'appointment' => [
                'id' => $appointment->id,
                'date' => $appointment->fecha,
                'time' => substr($appointment->hora, 0, 5),
                'status' => $appointment->estado,
                'service' => $appointment->servicio?->nombre ?? 'Consulta general',
            ],
        ], 201)->header('Cache-Control', 'no-store');
    }

    public function storeTreatmentAppointment(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'treatment_id' => ['required', 'integer', 'exists:casos_clinicos,id'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'date_format:H:i'],
        ]);

        $patient = $this->patientFor($request->user());
        $treatment = CasoClinico::whereKey($validated['treatment_id'])
            ->where('paciente_id', $patient->id)
            ->where('estado', CasoClinico::ESTADO_ACTIVO)
            ->firstOrFail();

        $appointment = DB::transaction(function () use ($validated, $patient, $treatment) {
            $time = $validated['time'] . ':00';
            $availableSlots = Cita::getHorariosDisponibles($validated['date']);

            if (!in_array($time, $availableSlots, true)) {
                throw ValidationException::withMessages([
                    'time' => 'Ese horario ya no está disponible. Actualiza los horarios y elige otro.',
                ]);
            }

            return Cita::create([
                'paciente_id' => $patient->id,
                'caso_clinico_id' => $treatment->id,
                'servicio_id' => null,
                'tratamiento_realizado' => $treatment->tratamiento_base,
                'fecha' => $validated['date'],
                'hora' => $time,
                'estado' => Cita::ESTADO_EN_ESPERA,
                'origen' => Cita::ORIGEN_APP,
            ]);
        });

        return response()->json([
            'message' => 'La cita para el tratamiento quedó pendiente de confirmación.',
            'appointment' => [
                'id' => $appointment->id,
                'date' => $appointment->fecha,
                'time' => substr($appointment->hora, 0, 5),
                'status' => $appointment->estado,
                'service' => $treatment->tratamiento_base,
            ],
        ], 201)->header('Cache-Control', 'no-store');
    }

    public function updateAppointmentStatus(Request $request, Cita $appointment): JsonResponse
    {
        if ((int) $request->user()->role_id === 3) {
            abort(403, 'Solo el personal del consultorio puede actualizar citas.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                Cita::ESTADO_EN_ESPERA,
                Cita::ESTADO_CONFIRMADA,
                Cita::ESTADO_REALIZADA,
                Cita::ESTADO_CANCELADA,
                Cita::ESTADO_NO_PRESENTO,
            ])],
        ]);

        $appointment->update(['estado' => $validated['status']]);

        return response()->json([
            'message' => 'El estado de la cita fue actualizado.',
        ], 200)->header('Cache-Control', 'no-store');
    }

    public function cancelAppointment(Request $request, Cita $appointment): JsonResponse
    {
        $patient = $this->patientFor($request->user());

        if ($appointment->paciente_id !== $patient->id) {
            abort(403, 'No puedes cancelar una cita que no te pertenece.');
        }

        if (!in_array($appointment->estado, [Cita::ESTADO_EN_ESPERA, Cita::ESTADO_CONFIRMADA], true)) {
            return response()->json([
                'message' => 'Esta cita ya no puede cancelarse.',
            ], 422)->header('Cache-Control', 'no-store');
        }

        $appointment->update(['estado' => Cita::ESTADO_CANCELADA]);

        return response()->json([
            'message' => 'La cita fue cancelada correctamente.',
        ])->header('Cache-Control', 'no-store');
    }

    private function staffDashboard(User $user): JsonResponse
    {
        $today = Carbon::today();
        $appointments = Cita::with(['paciente:id,nombre,apellidos', 'servicio:id,nombre'])
            ->whereDate('fecha', $today)
            ->orderBy('hora')
            ->limit(100)
            ->get()
            ->map(fn (Cita $appointment) => [
                'id' => $appointment->id,
                'date' => $appointment->fecha,
                'time' => substr($appointment->hora, 0, 5),
                'status' => $appointment->estado,
                'patient' => trim(($appointment->paciente->nombre ?? '') . ' ' . ($appointment->paciente->apellidos ?? '')),
                'service' => $appointment->tratamiento_realizado ?: ($appointment->servicio?->nombre ?? 'Consulta general'),
            ]);

        $recentPatients = Patient::latest()
            ->limit(10)
            ->get(['id', 'nombre', 'apellidos', 'telefono'])
            ->map(fn (Patient $patient) => [
                'id' => $patient->id,
                'name' => trim($patient->nombre . ' ' . $patient->apellidos),
                'phone' => $patient->telefono,
            ]);

        return response()->json([
            'role_id' => (int) $user->role_id,
            'staff' => [
                'name' => trim($user->nombre . ' ' . $user->apellidos),
                'username' => $user->username,
                'role_name' => (int) $user->role_id === 1 ? 'Administrador' : 'Asistente',
            ],
            'summary' => [
                'today_appointments' => Cita::whereDate('fecha', $today)->count(),
                'patients' => Patient::count(),
                'active_treatments' => CasoClinico::where('estado', 'activo')->where('progreso', '<', 100)->count(),
                'services' => Servicio::count(),
                'pending_requests' => \App\Models\SolicitudCita::where('estado', \App\Models\SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION)->count(),
            ],
            'appointments' => $appointments,
            'recent_patients' => $recentPatients,
        ], 200)->header('Cache-Control', 'no-store');
    }

    private function patientFor(User $user): Patient
    {
        return Patient::where('user_id', $user->id)->firstOrFail();
    }
}
