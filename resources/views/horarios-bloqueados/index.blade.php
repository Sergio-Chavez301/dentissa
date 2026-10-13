<x-plantilla>
    <x-slot:title>Horarios bloqueados | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Administrar horarios</h1>
        <p class="text-muted mb-0">Bloquea una hora por mantenimiento, imprevistos u otras causas. Las citas y solicitudes pendientes no se pueden bloquear.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-xl-5">
            <section class="card p-4 border-0 shadow-sm bg-white" aria-labelledby="nuevo-bloqueo">
                <h2 class="h5 fw-bold mb-3" id="nuevo-bloqueo">Bloquear un horario</h2>
                <form action="{{ route('horarios-bloqueados.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="fecha" class="form-label fw-semibold">Fecha</label>
                        <input class="form-control" id="fecha" name="fecha" type="date" min="{{ now()->toDateString() }}" value="{{ old('fecha') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="hora" class="form-label fw-semibold">Hora</label>
                        <select class="form-select" id="hora" name="hora" required>
                            <option value="">Selecciona una hora</option>
                            @foreach($horas as $hora)
                                <option value="{{ $hora }}" @selected(old('hora') === $hora)>{{ \Carbon\Carbon::parse($hora)->format('H:i') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="motivo" class="form-label fw-semibold">Motivo</label>
                        <input class="form-control" id="motivo" name="motivo" type="text" maxlength="255" value="{{ old('motivo') }}" placeholder="Ej. Mantenimiento del consultorio" required>
                    </div>
                    <button class="btn text-white bg-dentissa fw-semibold" type="submit">
                        <i class="bi bi-calendar-x me-1"></i> Bloquear hora
                    </button>
                </form>
            </section>
        </div>

        <div class="col-12 col-xl-7">
            <section class="card p-4 border-0 shadow-sm bg-white" aria-labelledby="bloqueos-actuales">
                <h2 class="h5 fw-bold mb-3" id="bloqueos-actuales">Bloqueos próximos</h2>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted small text-uppercase">
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Motivo</th>
                                <th>Registró</th>
                                <th><span class="visually-hidden">Acción</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bloqueos as $bloqueo)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($bloqueo->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($bloqueo->hora)->format('H:i') }}</td>
                                    <td>{{ $bloqueo->motivo }}</td>
                                    <td>{{ $bloqueo->usuario->nombre ?? 'Usuario eliminado' }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('horarios-bloqueados.destroy', $bloqueo) }}" method="POST" onsubmit="return confirm('¿Liberar este horario? Volverá a aparecer como disponible si no tiene una cita o solicitud pendiente.');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit" title="Liberar horario" aria-label="Liberar {{ \Carbon\Carbon::parse($bloqueo->fecha)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($bloqueo->hora)->format('H:i') }}">
                                                <i class="bi bi-unlock"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No hay horarios bloqueados próximos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-plantilla>