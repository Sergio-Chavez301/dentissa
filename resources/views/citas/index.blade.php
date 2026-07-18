<x-plantilla>
    <x-slot:title>Gestión de Citas | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Citas Programadas</h1>
            <p class="text-muted">Administra el calendario de atenciones de la clínica.</p>
        </div>
        <div>
            <a href="{{ route('citas.create') }}" class="btn text-white border-0 py-2 px-3 fw-semibold bg-dentissa shadow-sm">
                <i class="bi bi-calendar-plus-fill me-2"></i>Nueva Cita
            </a>
        </div>
    </div>

    <!-- Alertas de Éxito -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem;">
                        <th>Paciente</th>
                        <th>Servicio</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellidos ?? '' }}</td>
                            <td>{{ $cita->servicio->nombre ?? 'N/A' }}</td>
                            <td>
                                <i class="bi bi-calendar-event me-1 text-muted"></i> {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                <br>
                                <i class="bi bi-clock me-1 text-muted"></i> {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}
                            </td>
                            <td>
                                <!-- Colores personalizados para el estado -->
                                @if(strtolower($cita->estado) === 'confirmada')
                                    <span class="badge rounded-pill bg-success-subtle text-success px-2.5 py-1">Confirmada</span>
                                @elseif(strtolower($cita->estado) === 'pendiente')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning px-2.5 py-1">Pendiente</span>
                                @elseif(strtolower($cita->estado) === 'cancelada')
                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-2.5 py-1">Cancelada</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2.5 py-1">{{ ucfirst($cita->estado) }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-light border" title="Ver Detalles">
                                        <i class="bi bi-eye-fill text-muted"></i>
                                    </a>
                                    <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil-fill text-muted"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-3 d-block mb-2 text-secondary"></i>
                                No hay citas programadas actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>