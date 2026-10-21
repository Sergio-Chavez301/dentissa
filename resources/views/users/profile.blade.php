<x-plantilla>
    <x-slot:title>Mi Perfil | Dentissa</x-slot:title>

    @php
        $dashboardRoute = match ((int) Auth::user()->role_id) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.asistente'),
            3 => route('dashboard.paciente'),
            default => route('home'),
        };
    @endphp

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom">
        <div>
            <a href="{{ $dashboardRoute }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>Volver al panel
            </a>
            <h1 class="h2 fw-bold text-dark mb-1">Mi perfil</h1>
            <p class="text-muted mb-0">Actualiza tus datos de acceso y la información de tu cuenta.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            <i class="bi bi-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert">Revisa los campos marcados antes de guardar los cambios.</div>
    @endif

    <section class="card border-0 shadow-sm" style="max-width: 860px;">
        <div class="card-body p-4 p-lg-5">
            <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nombre" class="form-label fw-semibold">Nombre</label>
                        <input id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required maxlength="255" autocomplete="given-name" class="form-control @error('nombre') is-invalid @enderror">
                        @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="apellidos" class="form-label fw-semibold">Apellidos</label>
                        <input id="apellidos" name="apellidos" value="{{ old('apellidos', $usuario->apellidos) }}" required maxlength="255" autocomplete="family-name" class="form-control @error('apellidos') is-invalid @enderror">
                        @error('apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label fw-semibold">Nombre de usuario</label>
                        <input id="username" name="username" value="{{ old('username', $usuario->username) }}" required maxlength="50" autocomplete="username" class="form-control @error('username') is-invalid @enderror">
                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $usuario->email) }}" required maxlength="255" autocomplete="email" class="form-control @error('email') is-invalid @enderror">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="border-top mt-4 pt-4">
                    <h2 class="h6 fw-bold">Cambiar contraseña</h2>
                    <p class="small text-muted">Déjala vacía si no deseas cambiarla. Usa al menos 8 caracteres.</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold">Nueva contraseña</label>
                            <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold">Confirmar contraseña</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end flex-wrap gap-2 border-top mt-4 pt-3">
                    <a href="{{ $dashboardRoute }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-dentissa text-white">
                        <i class="bi bi-check2 me-1" aria-hidden="true"></i>Confirmar y guardar
                    </button>
                </div>
            </form>
        </div>
    </section>

    <script>
        document.getElementById('profile-form').addEventListener('submit', (event) => {
            if (!window.confirm('¿Confirmas que deseas guardar los cambios de tu perfil?')) {
                event.preventDefault();
            }
        });
    </script>
</x-plantilla>