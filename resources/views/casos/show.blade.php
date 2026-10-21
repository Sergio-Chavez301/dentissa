<x-plantilla>
    <x-slot:title>Detalle del Caso | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('casos.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Detalle del Caso</h1>
        <p class="text-muted">Consulta la información y el progreso del tratamiento.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <div class="row g-4">
            <div class="col-md-6">
                <label class="text-muted small text-uppercase fw-bold">Paciente</label>
                <div class="text-dark fw-semibold fs-5">{{ $caso->paciente->nombre }} {{ $caso->paciente->apellidos }}</div>
            </div>

            <div class="col-md-6">
                <label class="text-muted small text-uppercase fw-bold">Estado del Caso</label>
                <div>
                    @if($caso->estado === \App\Models\CasoClinico::ESTADO_ACTIVO)
                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i> Activo
                        </span>
                    @else
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">
                            <i class="bi bi-flag me-1"></i> {{ ucfirst($caso->estado) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-12">
                <label class="text-muted small text-uppercase fw-bold">Tratamiento Base</label>
                <div class="p-3 bg-light rounded border text-dark">
                    {{ $caso->tratamiento_base }}
                </div>
            </div>

            <div class="col-12">
                <label class="text-muted small text-uppercase fw-bold d-flex justify-content-between">
                    Progreso del Tratamiento <span>{{ $caso->progreso }}%</span>
                </label>
                <div class="progress mt-2" style="height: 25px; border-radius: 50px;">
                    <div class="progress-bar bg-dentissa" role="progressbar" style="width: {{ $caso->progreso }}%;" aria-valuenow="{{ $caso->progreso }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $caso->progreso }}%
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
            <a href="{{ route('casos.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                Volver
            </a>
            <a href="{{ route('casos.edit', $caso->id) }}" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                <i class="bi bi-pencil me-2"></i> Editar Progreso
            </a>
        </div>
    </div>

    <section class="mt-4" style="max-width: 800px;">
        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: #1e1e24;">Citas del tratamiento</h2>
                <p class="text-muted mb-0">Seguimiento de las visitas asociadas a este caso.</p>
            </div>
            @if(auth()->user()->role_id != 3)
                <a href="{{ route('citas.create', ['paciente_id' => $caso->paciente_id, 'caso_clinico_id' => $caso->id]) }}" class="btn text-white bg-dentissa flex-shrink-0">
                    <i class="bi bi-plus-lg me-1"></i> Agendar cita
                </a>
            @endif
        </div>

        <div class="card shadow-sm border-0 bg-white">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th>Fecha</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($caso->citas as $cita)
                            @php
                                $estadoCita = [
                                    'en_espera' => 'En espera',
                                    'realizada' => 'Realizada',
                                    'cancelada' => 'Cancelada',
                                    'no_presento' => 'No se presentó',
                                ][$cita->estado] ?? ucfirst($cita->estado);
                            @endphp
                            <tr>
                                <td class="text-nowrap">
                                    {{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                    <span class="text-muted ms-1">{{ substr($cita->hora, 0, 5) }}</span>
                                </td>
                                <td>{{ $cita->servicio->nombre ?? 'Servicio eliminado' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $estadoCita }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Todavía no hay citas asociadas a este tratamiento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-plantilla>