<x-plantilla>
    <x-slot:title>Editar Caso | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('casos.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Editar Progreso</h1>
        <p class="text-muted">Actualiza los datos del caso clínico seleccionado.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('casos.update', $caso->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Datos fijos para contexto -->
                <div class="col-md-6">
                    <label class="text-muted small text-uppercase fw-bold">Paciente</label>
                    <div class="p-2 bg-light rounded border text-dark fw-semibold">
                        {{ $caso->paciente->nombre }} {{ $caso->paciente->apellidos }}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="text-muted small text-uppercase fw-bold">Tratamiento Base</label>
                    <div class="p-2 bg-light rounded border text-dark fw-semibold">
                        {{ $caso->tratamiento_base }}
                    </div>
                </div>

                <!-- Campos de edición -->
                <div class="col-md-6">
                    <label for="progreso" class="form-label fw-semibold text-dark">Progreso Actual (%)</label>
                    <input type="number" class="form-control @error('progreso') is-invalid @enderror" id="progreso" name="progreso" value="{{ old('progreso', $caso->progreso) }}" min="0" max="100" required>
                    @error('progreso')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="estado" class="form-label fw-semibold text-dark">Estado del Caso</label>
                    <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                        <option value="activo" {{ old('estado', $caso->estado) == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="finalizado" {{ old('estado', $caso->estado) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                    @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('casos.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-check-lg me-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-plantilla>