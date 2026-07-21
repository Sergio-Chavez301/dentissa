<x-plantilla>
    <x-slot:title>Gestionar Solicitud | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('solicitudes.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Gestionar Solicitud</h1>
        <p class="text-muted">Revisa los datos y decide si confirmar o rechazar esta cita.</p>
    </div>

    <!-- Gestión de Alertas / Errores de Validación -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Por favor selecciona un servicio válido para confirmar la cita.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 600px;">
        <!-- Información Directa -->
        <div class="mb-4">
            <h5 class="fw-bold mb-3">Detalles de la Petición</h5>
            <div class="p-3 bg-light rounded">
                <p class="mb-2"><strong>Paciente / Prospecto:</strong> {{ $solicitud->nombre }} {{ $solicitud->apellidos }}</p>
                <p class="mb-2"><strong>Contacto:</strong> {{ $solicitud->email ?? 'No proporcionado' }} | {{ $solicitud->telefono }}</p>
                <p class="mb-2"><strong>Fecha de Nacimiento:</strong> {{ \Carbon\Carbon::parse($solicitud->fecha_nacimiento)->format('d/m/Y') }}</p>
                <p class="mb-2"><strong>Motivo:</strong> {{ $solicitud->motivo_consulta ?? 'Sin especificar' }}</p>
                <p class="mb-0"><strong>Fecha sugerida:</strong> {{ \Carbon\Carbon::parse($solicitud->fecha_hora_propuesta)->format('d/m/Y h:i A') }}</p>
            </div>
        </div>

        <!-- Formulario de Gestión -->
        <form action="{{ route('solicitudes.update', $solicitud->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Selector de Servicio (Obligatorio para confirmar y crear la cita oficial) -->
            <div class="mb-3">
                <label for="servicio_id" class="form-label fw-semibold text-dark">Asignar Servicio Clínico <span class="text-danger">*</span></label>
                <select name="servicio_id" id="servicio_id" class="form-select @error('servicio_id') is-invalid @enderror" required>
                    <option value="" selected disabled>-- Selecciona un servicio --</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }} (${{ number_format($servicio->precio, 2) }})</option>
                    @endforeach
                </select>
                @error('servicio_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Este servicio se asociará a la cita oficial que se creará automáticamente.</div>
            </div>

            <!-- Acciones -->
            <div class="d-flex gap-2 pt-2">
                <button type="submit" name="confirmar" value="1" class="btn text-white bg-dentissa py-2 px-4 fw-bold shadow-sm">
                    <i class="bi bi-check-lg me-1"></i> Confirmar Cita y Crear Paciente
                </button>
                
                <button type="submit" name="estado" value="{{ \App\Models\SolicitudCita::ESTADO_CANCELADA }}" class="btn btn-outline-danger py-2 px-4 fw-bold">
                    <i class="bi bi-x-lg me-1"></i> Rechazar
                </button>
            </div>
        </form>
    </div>
</x-plantilla>