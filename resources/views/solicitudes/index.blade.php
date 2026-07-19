<x-plantilla>
    <x-slot:title>Solicitudes Web | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Solicitudes de Cita Web</h1>
        <p class="text-muted">Gestión de peticiones recibidas desde el portal de pacientes.</p>
    </div>

    <!-- Gestión de Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th>Paciente</th>
                        <th>Fecha Propuesta</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $solicitud->nombre }} {{ $solicitud->apellidos }}</div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($solicitud->fecha_hora_propuesta)->format('d/m/Y h:i A') }}</td>
                            
                            <td>
                                @switch($solicitud->estado)
                                    @case(\App\Models\SolicitudCita::ESTADO_ESPERANDO_CONFIRMACION)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-2">Esperando Confirmación</span>
                                        @break
                                    @case(\App\Models\SolicitudCita::ESTADO_CONFIRMADA)
                                        <span class="badge rounded-pill bg-success-subtle text-success px-2">Confirmada</span>
                                        @break
                                    @case(\App\Models\SolicitudCita::ESTADO_CANCELADA)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-2">Cancelada</span>
                                        @break
                                    @default
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2">{{ ucfirst($solicitud->estado) }}</span>
                                @endswitch
                            </td>

                            <td class="text-end">
                                <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="btn btn-sm text-white shadow-sm bg-dentissa px-3">
                                    Gestionar <i class="bi bi-arrow-right-short ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-light"></i>
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>