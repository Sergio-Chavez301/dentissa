<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\CasoClinico;
use App\Models\Oferta;
use App\Models\Patient;
use App\Models\PushDevice;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

        $user = User::where('username', $credentials['username'])
            ->where('activo', true)
            ->where('role_id', 3)
            ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Usuario o contraseña incorrectos.',
            ], 401)->header('Cache-Control', 'no-store');
        }

        if (!Patient::where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'La cuenta no tiene un perfil de paciente asociado. Contacta al consultorio.',
            ], 403)->header('Cache-Control', 'no-store');
        }

        $token = $user->createToken('dentissa-android')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'name' => trim($user->nombre . ' ' . $user->apellidos),
            ],
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
                'service' => $appointment->servicio?->nombre ?? 'Consulta general',
                'treatment' => $appointment->casoClinico?->tratamiento_base,
            ]);

        $appointmentCounts = Cita::where('paciente_id', $patient->id)
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado');

        $treatments = CasoClinico::with([
            'citas' => fn ($query) => $query
                ->reorder()
                ->where('estado', Cita::ESTADO_REALIZADA)
                ->with('servicio:id,nombre')
                ->orderByDesc('fecha')
                ->orderByDesc('hora'),
        ])
            ->where('paciente_id', $patient->id)
            ->get()
            ->map(fn (CasoClinico $treatment) => [
                'name' => $treatment->tratamiento_base ?: 'Tratamiento dental',
                'progress' => min(100, max(0, (int) $treatment->progreso)),
                'status' => $treatment->estado,
                'completed_appointments' => $treatment->citas->map(fn (Cita $appointment) => [
                    'service' => $appointment->servicio?->nombre ?? 'Consulta general',
                    'date' => $appointment->fecha,
                    'time' => substr($appointment->hora, 0, 5),
                ])->values(),
            ]);

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
            ]);

        return response()->json([
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

    private function patientFor(User $user): Patient
    {
        return Patient::where('user_id', $user->id)->firstOrFail();
    }
}
