<x-plantilla>
    <x-slot:title>Ficha del Paciente | Dentissa</x-slot:title>

    <!-- Header con botón de volver y acciones -->
    <div class="pb-2 mb-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            @if(Auth::user()->role_id == 3)
                <a href="{{ route('dashboard.paciente') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Volver al inicio
                </a>
            @else
                <a href="{{ route('patients.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
                    <i class="bi bi-arrow-left me-1"></i> Volver al listado
                </a>
            @endif
            <h1 class="h2 fw-bold" style="color: #1e1e24;">
                @if(Auth::user()->role_id == 3) Mi Expediente Personal @else Ficha del Paciente @endif
            </h1>
        </div>

        <!-- Ocultar botones de edición y eliminación si el usuario es un paciente (role_id == 3) -->
        @if(Auth::user()->role_id != 3)
            <div class="d-flex gap-2">
                <!-- Botón Editar -->
                <a href="{{ route('patients.edit', $patient->id) }}" class="btn text-white border-0 py-2 px-3 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-pencil-fill me-2"></i> Editar Información
                </a>

                <!-- Botón Eliminar con Alerta de Confirmación -->
                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este paciente? Esta acción borrará permanentemente su cuenta de acceso, sus citas y todos sus registros.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger text-white border-0 py-2 px-3 fw-semibold shadow-sm">
                        <i class="bi bi-trash-fill me-2"></i> Eliminar Paciente
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Alertas de Éxito o Error Generales -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="max-width: 900px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- CONTENEDOR DE CREDENCIALES Y RESTABLECIMIENTO (Solo visible para Admin/Asistente o si aplica) -->
    @if(Auth::user()->role_id != 3 && $patient->user)
        @php
            $telefonoLimpio = preg_replace('/[^0-9]/', '', session('temp_telefono') ?? $patient->telefono ?? '');
            $username = session('temp_username') ?? $patient->user->username;
            $passwordTemporal = session('temp_password');

            if ($passwordTemporal) {
                $mensaje = "Hola " . (session('temp_nombre') ?? $patient->nombre) . ", tus credenciales de acceso a Dentissa son:\nUsuario: " . $username . "\nContraseña temporal: " . $passwordTemporal . "\nInicia sesión en: " . url('/login');
            } else {
                $mensaje = "Hola " . $patient->nombre . ", te recordamos que tu usuario de acceso a Dentissa es: " . $username . "\nInicia sesión en: " . url('/login');
            }

            $urlWhatsApp = !empty($telefonoLimpio) ? "https://wa.me/" . $telefonoLimpio . "?text=" . urlencode($mensaje) : "#";
        @endphp

        <div class="card shadow-sm border-0 mb-4 bg-light-subtle border-start border-4 border-success p-3" style="max-width: 900px;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div style="flex: 1; min-width: 280px;">
                    <h6 class="text-success fw-bold mb-1"><i class="bi bi-shield-lock-fill me-2"></i>Cuenta de Acceso Web del Paciente</h6>
                    <p class="mb-0 text-muted small">
                        Usuario: <code class="fw-bold bg-white text-dark px-2 py-1 rounded border">{{ $username }}</code>
                        @if($passwordTemporal)
                            <span class="ms-2">Contraseña temporal: <code class="fw-bold bg-white text-dark px-2 py-1 rounded border text-danger">{{ $passwordTemporal }}</code></span>
                        @else
                            <span class="ms-2 text-muted">(Contraseña protegida / Encriptada)</span>
                        @endif
                        <span class="ms-2">Estado: <span class="badge bg-success-subtle text-success">Activo</span></span>
                    </p>
                </div>
                
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Botón para generar nueva contraseña -->
                    <form action="{{ route('patients.whatsapp', $patient->id) }}" method="GET" onsubmit="return confirm('¿Deseas generar una nueva contraseña para este paciente? La anterior dejará de funcionar.');">
                        <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1 px-3 py-2 fw-semibold">
                            <i class="bi bi-key-fill"></i> Generar Contraseña
                        </button>
                    </form>

                    <!-- Botón de WhatsApp -->
                    @if(!empty($telefonoLimpio))
                        <a href="{{ $urlWhatsApp }}" target="_blank" class="btn btn-success btn-sm d-inline-flex align-items-center gap-2 px-3 py-2 fw-semibold shadow-sm">
                            <i class="bi bi-whatsapp fs-5"></i> Enviar por WhatsApp
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled title="El paciente no tiene un teléfono registrado">
                            <i class="bi bi-whatsapp"></i> Sin teléfono
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

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
                <p class="fw-semibold text-dark fs-5">{{ $patient->telefono ?? 'No registrado' }}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Correo</label>
                <p class="fw-semibold text-dark fs-5">{{ $patient->email ?? 'No registrado' }}</p>
            </div>
            <div class="col-md-4">
                <label class="text-muted small">Fecha de Nacimiento</label>
                <p class="fw-semibold text-dark fs-5">{{ $patient->fecha_nacimiento ?? 'No registrada' }}</p>
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
                            <td>{{ $cita->motivo ?? 'Consulta general' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-center text-muted py-3">No hay citas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>