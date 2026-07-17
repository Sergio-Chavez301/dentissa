<x-plantilla>
    <x-slot:title>Editar Personal | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Editar Información de Personal</h1>
        <p class="text-muted">Modifica los datos del empleado o actualiza su rol de acceso al sistema.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('users.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Nombre -->
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-semibold text-dark">Nombre(s)</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Apellidos -->
                <div class="col-md-6">
                    <label for="apellidos" class="form-label fw-semibold text-dark">Apellidos</label>
                    <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required>
                    @error('apellidos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nombre de Usuario -->
                <div class="col-md-6">
                    <label for="username" class="form-label fw-semibold text-dark">Nombre de Usuario (Para iniciar sesión)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">@</span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $usuario->username) }}" required>
                    </div>
                    @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold text-dark">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Rol / Puesto -->
                <div class="col-md-12">
                    <label for="role_id" class="form-label fw-semibold text-dark">Puesto / Rol en la clínica</label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="1" {{ old('role_id', $usuario->role_id) == 1 ? 'selected' : '' }}>Administrador (Acceso total)</option>
                        <option value="2" {{ old('role_id', $usuario->role_id) == 2 ? 'selected' : '' }}>Asistente</option>
                        <option value="3" {{ old('role_id', $usuario->role_id) == 3 ? 'selected' : '' }}>Odontólogo</option>
                        <option value="4" {{ old('role_id', $usuario->role_id) == 4 ? 'selected' : '' }}>Recepcionista</option>
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Interruptor de Estado de Cuenta -->
                <div class="col-12 mt-4">
                    <label class="form-label fw-semibold text-dark">Estado de Acceso</label>
                    <div class="card bg-light border-0 p-3">
                        <div class="form-check form-switch d-flex align-items-center">
                            <input class="form-check-input" type="checkbox" name="activo" id="activo" 
                                value="1" 
                                {{ old('activo', $usuario->activo) == 1 ? 'checked' : '' }} 
                                style="transform: scale(1.3);">
                            <label class="form-check-label ms-3 fw-medium text-dark" for="activo">
                                Permitir acceso al sistema (Usuario Activo)
                            </label>
                        </div>
                        <small class="text-muted mt-2 ps-1">
                            Si desactivas esta opción, el usuario no podrá iniciar sesión aunque sus credenciales sean correctas.
                        </small>
                    </div>
                </div>

                <!-- Aviso sobre contraseña -->
                <div class="col-12 mt-4">
                    <div class="alert alert-warning border-0 shadow-sm py-2 px-3 mb-1" style="font-size: 0.9rem;">
                        <i class="bi bi-info-circle-fill me-2"></i> Deja los siguientes campos vacíos si <strong>no</strong> deseas cambiar la contraseña actual del usuario.
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold text-dark">Nueva Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Opcional">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirmar Nueva Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Opcional">
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-arrow-repeat me-2"></i> Actualizar Registro
                </button>
            </div>
        </form>
    </div>
</x-plantilla>