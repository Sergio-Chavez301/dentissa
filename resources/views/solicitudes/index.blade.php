<x-plantilla>
    <x-slot:title>Solicitudes de Cita | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Solicitudes de Cita</h1>
        <p class="text-muted">Gestiona las peticiones recibidas desde la web y la app de pacientes.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            @if(session('whatsapp_url'))
                <a href="{{ session('whatsapp_url') }}" target="_blank" rel="noopener" class="btn btn-success btn-sm ms-3">
                    <i class="bi bi-whatsapp me-1"></i>{{ session('whatsapp_label', 'Enviar WhatsApp') }}
                </a>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th>Paciente</th>
                        <th>Origen</th>
                        <th>Fecha Propuesta</th>
                        <th>Estado</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @if($solicitudes->isEmpty() && $solicitudesApp->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-light"></i>
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                    @else
                    @foreach($solicitudes as $solicitud)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $solicitud->nombre }} {{ $solicitud->apellidos }}</div>
                            </td>
                            <td><span class="badge rounded-pill bg-info-subtle text-info px-2">Web</span></td>
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
                    @endforeach
                    @foreach($solicitudesApp as $solicitudApp)
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $solicitudApp->paciente?->nombre ?? 'Paciente' }}
                                    {{ $solicitudApp->paciente?->apellidos }}
                                </div>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $solicitudApp->origen === \App\Models\Cita::ORIGEN_APP ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }} px-2">
                                    {{ $solicitudApp->origen === \App\Models\Cita::ORIGEN_APP ? 'App' : 'Origen anterior' }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($solicitudApp->fecha . ' ' . $solicitudApp->hora)->format('d/m/Y h:i A') }}</td>
                            <td><span class="badge rounded-pill bg-warning-subtle text-warning px-2">Esperando Confirmación</span></td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <form action="{{ route('citas.status', $solicitudApp) }}" method="POST" class="m-0">
                                        @csrf
                                        <input type="hidden" name="estado" value="{{ \App\Models\Cita::ESTADO_CONFIRMADA }}">
                                        <input type="hidden" name="return_to" value="solicitudes.index">
                                        <button type="submit" class="btn btn-sm btn-success">Confirmar</button>
                                    </form>
                                    <form action="{{ route('citas.status', $solicitudApp) }}" method="POST" class="m-0" onsubmit="return confirm('¿Rechazar y cancelar esta solicitud?');">
                                        @csrf
                                        <input type="hidden" name="estado" value="{{ \App\Models\Cita::ESTADO_CANCELADA }}">
                                        <input type="hidden" name="return_to" value="solicitudes.index">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>