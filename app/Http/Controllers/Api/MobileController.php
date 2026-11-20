<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Patient;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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

    public function dashboard(Request $request): JsonResponse
    {
        $patient = $this->patientFor($request->user());

        $appointments = Cita::with('servicio:id,nombre')
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
            ]);

        return response()->json([
            'patient' => [
                'name' => trim($patient->nombre . ' ' . $patient->apellidos),
                'email' => $patient->email,
                'phone' => $patient->telefono,
            ],
            'appointments' => $appointments,
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
