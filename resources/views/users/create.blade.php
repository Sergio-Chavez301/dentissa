<x-plantilla>
    <x-slot:title>Registrar Personal | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Registrar Nuevo Personal</h1>
        <p class="text-muted">Crea una cuenta de acceso para los odontólogos, asistentes o administradores de la clínica.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Nombre -->
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-semibold text-dark">Nombre(s)</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Apellidos -->
                <div class="col-md-6">
                    <label for="apellidos" class="form-label fw-semibold text-dark">Apellidos</label>
                    <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
                    @error('apellidos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Nombre de Usuario -->
                <div class="col-md-6">
                    <label for="username" class="form-label fw-semibold text-dark">Nombre de Usuario (Para iniciar sesión)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">@</span>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="ej: melissa.lopez" required>
                    </div>
                    @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Correo Electrónico -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold text-dark">Correo Electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@dentissa.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Rol / Puesto (Dinámico desde la BD) -->
                <div class="col-md-12">
                    <label for="role_id" class="form-label fw-semibold text-dark">Puesto / Rol en la clínica</label>
                    <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                        <option value="" disabled selected>Selecciona una opción...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold text-dark">Contraseña</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirmar Contraseña -->
                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label fw-semibold text-dark">Confirmar Contraseña</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>
            </div>

            <!-- Botones de Acción con los colores del panel -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-save me-2"></i> Guardar Registro
                </button>
            </div>
        </form>
    </div>
</x-plantilla>