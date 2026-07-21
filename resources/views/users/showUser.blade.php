<x-plantilla>
    <x-slot:title>Perfil de Usuario | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Perfil del Personal</h1>
        <p class="text-muted">Información detallada de la cuenta de acceso del empleado.</p>
    </div>

    <div class="row g-4">
        <!-- Tarjeta Principal de Perfil -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 bg-white text-center">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-light" style="width: 100px; height: 100px; margin: 0 auto;">
                    <i class="bi bi-person-circle" style="font-size: 4.5rem; color: #d75078;"></i>
                </div>
                
                <h4 class="fw-bold mb-1" style="color: #1e1e24;">{{ $usuario->nombre }}</h4>
                <p class="text-muted mb-3">{{ $usuario->apellidos }}</p>

                <!-- Distintivo de Rol Dinámico desde la BD -->
                <div class="mb-4">
                    @if($usuario->role)
                        <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-1.5 fs-7">
                            {{ ucfirst($usuario->role->name) }}
                        </span>
                    @else
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-1.5 fs-7">Sin Rol</span>
                    @endif
                </div>

                <hr class="text-muted opacity-25">

                <!-- Acciones rápidas sobre el usuario -->
                <div class="d-grid gap-2 mt-3">
                    <a href="{{ route('users.edit', $usuario->id) }}" class="btn btn-light border fw-semibold">
                        <i class="bi bi-pencil-fill me-1"></i> Editar Datos
                    </a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Detalles del Sistema -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0 bg-white h-100">
                <h5 class="fw-bold pb-2 mb-4 border-bottom text-dark">Datos de la Cuenta</h5>
                
                <div class="row g-4">
                    <!-- Nombre de Usuario -->
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small uppercase fw-semibold" style="letter-spacing: 0.5px;">Nombre de Usuario</p>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark border px-2.5 py-1.5 fs-6">
                                @{{ $usuario->username }}
                            </span>
                        </div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small uppercase fw-semibold" style="letter-spacing: 0.5px;">Correo Electrónico</p>
                        <p class="fs-6 text-dark fw-medium">{{ $usuario->email ?? 'No registrado' }}</p>
                    </div>

                    <!-- Fecha de Registro -->
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small uppercase fw-semibold" style="letter-spacing: 0.5px;">Fecha de Registro</p>
                        <p class="fs-6 text-dark fw-medium">
                            {{ $usuario->created_at ? $usuario->created_at->format('d/m/Y a las h:i A') : 'No registrada' }}
                        </p>
                    </div>

                    <!-- Estado de Cuenta -->
                    <div class="col-md-6">
                        <p class="text-muted mb-1 small uppercase fw-semibold">Estado de Acceso</p>
                        <p class="fs-6 text-dark fw-medium">
                            @if($usuario->activo)
                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Activo</span>
                                
                                @if(method_exists($usuario, 'isOnline') && $usuario->isOnline())
                                    <span class="badge bg-success-subtle text-success ms-2">En línea</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary ms-2">Desconectado</span>
                                @endif
                            @else
                                <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i> Bloqueado (Sin acceso)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-plantilla>