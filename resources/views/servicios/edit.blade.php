<x-plantilla>
    <x-slot:title>Editar Servicio | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('servicios.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Editar Servicio</h1>
        <p class="text-muted">Modifica los datos del servicio: <strong>{{ $servicio->nombre }}</strong></p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('servicios.update', $servicio->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Nombre -->
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-semibold text-dark">Nombre del Servicio</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Precio -->
                <div class="col-md-6">
                    <label for="precio" class="form-label fw-semibold text-dark">Precio ($)</label>
                    <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $servicio->precio) }}" required>
                    @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Descripción -->
                <div class="col-md-12">
                    <label for="descripcion" class="form-label fw-semibold text-dark">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('servicios.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-save me-2"></i> Actualizar Servicio
                </button>
            </div>
        </form>
    </div>
</x-plantilla>