<x-plantilla>
    <x-slot:title>Editar Cita | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('citas.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Editar Cita #{{ $cita->id }}</h1>
        <p class="text-muted">Actualiza la información de la cita o cambia su estado actual.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('citas.update', $cita->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Información de solo lectura (Opcional) -->
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark">Paciente</label>
                    <input type="text" class="form-control bg-light" value="{{ $cita->paciente->nombre }} {{ $cita->paciente->apellidos }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark">Servicio</label>
                    <input type="text" class="form-control bg-light" value="{{ $cita->servicio->nombre }}" disabled>
                </div>

                <!-- Fecha y Hora -->
                <div class="col-md-6">
                    <label for="fecha" class="form-label fw-semibold text-dark">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', $cita->fecha) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="hora" class="form-label fw-semibold text-dark">Hora</label>
                    <input type="time" class="form-control" id="hora" name="hora" value="{{ old('hora', $cita->hora) }}" required>
                </div>

                <!-- Estado -->
                <div class="col-md-12 mt-3">
                    <label for="estado" class="form-label fw-semibold text-dark">Estado de la Cita</label>
                    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        <option value="pendiente" {{ old('estado', $cita->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmada" {{ old('estado', $cita->estado) == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        <option value="cancelada" {{ old('estado', $cita->estado) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('citas.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-arrow-repeat me-2"></i> Actualizar Cita
                </button>
            </div>
        </form>
    </div>
</x-plantilla>