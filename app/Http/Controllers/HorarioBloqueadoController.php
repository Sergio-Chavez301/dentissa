<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\HorarioBloqueado;
use App\Models\SolicitudCita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HorarioBloqueadoController extends Controller
{
    public function index()
    {
        $this->ensureAdminAccess();

        $bloqueos = HorarioBloqueado::with('usuario')
            ->whereDate('fecha', '>=', today())
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('horarios-bloqueados.index', [
            'bloqueos' => $bloqueos,
            'horas' => Cita::getBloquesAtencion(),
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureAdminAccess();

        $validated = $request->validate([
            'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
            'hora' => [
                'required',
                Rule::in(Cita::getBloquesAtencion()),
                Rule::unique('horarios_bloqueados', 'hora')->where(fn ($query) => $query->where('fecha', $request->input('fecha'))),
            ],
            'motivo' => 'required|string|max:255',
        ], [
            'hora.unique' => 'Ese horario ya está bloqueado para la fecha seleccionada.',
        ]);

        if (Carbon::parse($validated['fecha'] . ' ' . $validated['hora'])->isPast()) {
            return back()->withInput()->withErrors([
                'hora' => 'No se puede bloquear una hora que ya pasó.',
            ]);
        }

        $citaActiva = Cita::where('fecha', $validated['fecha'])
            ->where('hora', $validated['hora'])
            ->where('estado', '!=', Cita::ESTADO_CANCELADA)
            ->exists();
        $solicitudPendiente = SolicitudCita::whereDate('fecha_hora_propuesta', $validated['fecha'])
            ->whereTime('fecha_hora_propuesta', $validated['hora'])
            ->where('estado', SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION)
            ->exists();

        if ($citaActiva || $solicitudPendiente) {
            return back()->withInput()->withErrors([
                'hora' => 'Ese horario ya tiene una cita o una solicitud pendiente. Resuélvela antes de bloquearlo.',
            ]);
        }

        HorarioBloqueado::create([
            ...$validated,
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('horarios-bloqueados.index')->with('success', 'Horario bloqueado correctamente.');
    }

    public function destroy(HorarioBloqueado $horarioBloqueado)
    {
        $this->ensureAdminAccess();

        $horarioBloqueado->delete();

        return redirect()->route('horarios-bloqueados.index')->with('success', 'Horario liberado correctamente.');
    }

    private function ensureAdminAccess(): void
    {
        abort_unless((int) Auth::user()->role_id === 1, 403);
    }
}