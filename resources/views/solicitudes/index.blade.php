<x-plantilla>
    <x-slot:title>Solicitudes Web | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Solicitudes de Cita Web</h1>
        <p class="text-muted">Gestión de peticiones recibidas desde el portal de pacientes.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem;">
                        <th>Paciente</th>
                        <th>Contacto</th>
                        <th>Motivo</th>
                        <th>Fecha Propuesta</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitudes as $solicitud)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $solicitud->nombre }} {{ $solicitud->apellidos }}</div>
                            </td>
                            <td>
                                <small class="text-muted d-block"><i class="bi bi-envelope me-1"></i>{{ $solicitud->email }}</small>
                                <small class="text-muted d-block"><i class="bi bi-phone me-1"></i>{{ $solicitud->telefono }}</small>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($solicitud->motivo_consulta, 30) }}</td>
                            <td>{{ \Carbon\Carbon::parse($solicitud->fecha_hora_propuesta)->format('d/m/Y h:i A') }}</td>
                            <td>
                                @if($solicitud->estado == 'pendiente')
                                    <span class="badge rounded-pill bg-warning-subtle text-warning px-2">Pendiente</span>
                                @else
                                    <span class="badge rounded-pill bg-success-subtle text-success px-2">Confirmada</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="btn btn-sm btn-light border">
                                    <i class="bi bi-eye-fill text-muted"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4 text-muted">No hay solicitudes pendientes.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>