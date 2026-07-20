<x-plantilla>
    <x-slot:title>Ficha del Paciente | Dentissa</x-slot:title>

    <!-- Header con botón de volver -->
    <div class="pb-2 mb-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <a href="{{ route('patients.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
                <i class="bi bi-arrow-left me-1"></i> Volver al listado
            </a>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Ficha del Paciente</h1>
        </div>
        <a href="{{ route('patients.edit', $patient->id) }}" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
            <i class="bi bi-pencil-fill me-2"></i> Editar Información
        </a>
    </div>

    <!-- Datos Personales y Médicos -->
    <div class="card p-4 shadow-sm border-0 bg-white mb-4" style="max-width: 900px;">
        <h5 class="text-muted text-uppercase small fw-bold mb-4">Información Personal</h5>
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="text-muted small">Nombre Completo</label>
                <p class="fw-semibold text-dark fs-5">{{ $patient->nombre }} {{ $patient->apellidos }}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Teléfono</label>
                <p class="fw-semibold text-dark fs-5">{{ $patient->telefono }}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Correo</label>
                <p class="fw-semibold text-dark fs-5">{{ $patient->email ?? 'No registrado' }}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Fecha de Nacimiento</label>
                <p class="fw-semibold text-dark fs-5">{{ $patient->fecha_nacimiento }}</p>
            </div>
        </div>

        <hr class="text-muted">

        <h5 class="text-muted text-uppercase small fw-bold mt-3 mb-3">Información Médica</h5>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="text-muted small">Alergias</label>
                <div class="p-2 bg-light rounded border-start border-4 border-danger">
                    <p class="mb-0 text-dark">{{ $patient->alergias ?? 'Ninguna registrada' }}</p>
                </div>
            </div>
            <div class="col-md-12 mb-3">
                <label class="text-muted small">Enfermedades Crónicas</label>
                <div class="p-2 bg-light rounded border-start border-4 border-primary">
                    <p class="mb-0 text-dark">{{ $patient->enfermedades ?? 'Ninguna registrada' }}</p>
                </div>
            </div>
            <div class="col-md-12">
                <label class="text-muted small">Tratamientos Actuales</label>
                <div class="p-2 bg-light rounded border-start border-4 border-info">
                    <p class="mb-0 text-dark">{{ $patient->tratamientos ?? 'Ninguno registrado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Casos Clínicos -->
    <div class="card p-4 shadow-sm border-0 bg-white mb-4" style="max-width: 900px;">
        <h5 class="text-muted text-uppercase small fw-bold mb-3">Casos Clínicos</h5>
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="bg-light">
                    <tr class="text-muted" style="font-size: 0.85rem;">
                        <th>Tratamiento</th>
                        <th>Progreso</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patient->casosClinicos as $caso)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $caso->tratamiento_base }}</td>
                            <td>
                                <div class="progress" style="height: 10px; max-width: 150px;">
                                    <div class="progress-bar bg-dentissa" style="width: {{ $caso->progreso }}%"></div>
                                </div>
                                <small class="text-muted">{{ $caso->progreso }}%</small>
                            </td>
                            <td>
                                <span class="badge {{ $caso->estado == 'activo' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                    {{ ucfirst($caso->estado) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-3">No hay casos clínicos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Citas -->
    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 900px;">
        <h5 class="text-muted text-uppercase small fw-bold mb-3">Historial de Citas</h5>
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="bg-light">
                    <tr class="text-muted" style="font-size: 0.85rem;">
                        <th>Fecha</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patient->citas as $cita)
                        <tr>
                            <td>{{ $cita->fecha }}</td>
                            <td>{{ $cita->motivo }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">No hay citas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>