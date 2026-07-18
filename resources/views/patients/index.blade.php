<x-plantilla>
    <x-slot:title>Gestión de Pacientes | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Listado de Pacientes</h1>
            <p class="text-muted">Administra los registros y datos de los pacientes de la clínica.</p>
        </div>
        <div>
            <a href="{{ route('patients.create') }}" class="btn text-white border-0 py-2 px-3 fw-semibold bg-dentissa shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i> Registrar Paciente
            </a>
        </div>
    </div>

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
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Fecha de Nacimiento</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $patient->nombre }} {{ $patient->apellidos }}</td>
                            <td>{{ $patient->telefono }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-2.5 py-1">
                                    {{ $patient->fecha_nacimiento }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <!-- Ver -->
                                    <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-sm btn-light border" title="Ver Detalles">
                                        <i class="bi bi-eye-fill text-muted"></i>
                                    </a>
                                    <!-- Editar -->
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil-fill text-muted"></i>
                                    </a>
                                    <!-- Eliminar -->
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a este paciente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border" title="Eliminar">
                                            <i class="bi bi-trash-fill text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="bi bi-person-x fs-3 d-block mb-2 text-secondary"></i>
                                No hay pacientes registrados en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>