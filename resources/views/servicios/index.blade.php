<x-plantilla>
    <x-slot:title>Gestión de Servicios | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Catálogo de Servicios</h1>
            <p class="text-muted">Administra los servicios ofrecidos por la clínica.</p>
        </div>
        <div>
            <a href="{{ route('servicios.create') }}" class="btn text-white border-0 py-2 px-3 fw-semibold bg-dentissa shadow-sm">
                <i class="bi bi-plus-lg me-2"></i>Nuevo Servicio
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
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($servicios as $servicio)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $servicio->nombre }}</td>
                            <td class="text-muted">{{ $servicio->descripcion ?? 'Sin descripción' }}</td>
                            <td class="fw-medium text-dark">${{ number_format($servicio->precio, 2) }}</td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <!-- Botón Editar -->
                                    <a href="{{ route('servicios.edit', $servicio->id) }}" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil-fill text-muted"></i>
                                    </a>

                                    <!-- Botón Eliminar -->
                                    <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="m-0" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
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
                                <i class="bi bi-clipboard-x fs-3 d-block mb-2 text-secondary"></i>
                                No hay servicios registrados actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>