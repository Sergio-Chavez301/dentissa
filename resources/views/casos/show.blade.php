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
            <!-- Información del Paciente -->
            <div class="col-md-6">
                <label class="text-muted small text-uppercase fw-bold">Paciente</label>
                <div class="text-dark fw-semibold fs-5">{{ $caso->paciente->nombre }} {{ $caso->paciente->apellidos }}</div>
            </div>

            <!-- Estado -->
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

            <!-- Tratamiento -->
            <div class="col-12">
                <label class="text-muted small text-uppercase fw-bold">Tratamiento Base</label>
                <div class="p-3 bg-light rounded border text-dark">
                    {{ $caso->tratamiento_base }}
                </div>
            </div>

            <!-- Progreso -->
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

        <!-- Botones de Acción -->
        <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
            <a href="{{ route('casos.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                Volver
            </a>
            <a href="{{ route('casos.edit', $caso->id) }}" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                <i class="bi bi-pencil me-2"></i> Editar Progreso
            </a>
        </div>
    </div>
</x-plantilla>