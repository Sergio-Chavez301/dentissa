<x-plantilla>
    <x-slot:title>Gestión de Usuarios | Personal de la Clínica</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Personal de la Clínica</h1>
            <p class="text-muted">Administra las cuentas de acceso de los odontólogos y el personal administrativo.</p>
        </div>
        <div>
            <!-- Botón para ir al formulario de creación -->
            <a href="{{ route('users.create') }}" class="btn text-white border-0 py-2 px-3 fw-semibold bg-dentissa shadow-sm">
                <i class="bi bi-person-plus-fill me-2"></i> Registrar Personal
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

    <!-- Tabla de Usuarios -->
    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem;">
                        <th>Usuario</th>
                        <th>Nombre Completo</th>
                        <th>Correo Electrónico</th>
                        <th>Rol / Puesto</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td class="fw-semibold text-dark">
                                <span class="badge bg-light text-dark border px-2.5 py-1">
                                    {{ $usuario->username }}
                                </span>
                            </td>
                            <td>{{ $usuario->nombre }} {{ $usuario->apellidos }}</td>
                            <td>{{ $usuario->email ?? 'Sin correo registrado' }}</td>
                            <td>
                                <!-- Colores personalizados dependiendo del Rol -->
                                @if($usuario->role_id === 1)
                                    <span class="badge rounded-pill bg-danger-subtle text-danger px-2.5 py-1">Administrador</span>
                                @elseif($usuario->role_id === 2)
                                    <span class="badge rounded-pill bg-primary-subtle text-primary px-2.5 py-1">Asistente</span>
                                @elseif($usuario->role_id === 3)
                                    <span class="badge rounded-pill bg-info-subtle text-info px-2.5 py-1">Odontólogo</span>
                                @elseif($usuario->role_id === 4)
                                    <span class="badge rounded-pill bg-success-subtle text-success px-2.5 py-1">Recepcionista</span>
                                @else
                                    <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2.5 py-1">{{ $usuario->role?->name ?? 'Sin Rol' }}</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <!-- Ver Perfil -->
                                    <a href="{{ route('users.show', $usuario->id) }}" class="btn btn-sm btn-light border" title="Ver Detalles">
                                        <i class="bi bi-eye-fill text-muted"></i>
                                    </a>
                                    <!-- Editar -->
                                    <a href="{{ route('users.edit', $usuario->id) }}" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil-fill text-muted"></i>
                                    </a>
                                    <!-- Eliminar (No permitir que el admin se borre a sí mismo) -->
                                    @if(Auth::id() !== $usuario->id)
                                        <form action="{{ route('users.destroy', $usuario->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border" title="Eliminar">
                                                <i class="bi bi-trash-fill text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-people fs-3 d-block mb-2 text-secondary"></i>
                                No hay personal registrado en el sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>