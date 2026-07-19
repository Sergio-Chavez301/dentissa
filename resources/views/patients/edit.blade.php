<x-plantilla>
    <x-slot:title>Editar Paciente | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('patients.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Editar Información</h1>
        <p class="text-muted">Modifica los datos del paciente: <strong>{{ $patient->nombre }} {{ $patient->apellidos }}</strong></p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Nombre -->
                <div class="col-md-6">
                    <label for="nombre" class="form-label fw-semibold text-dark">Nombre(s)</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $patient->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Apellidos -->
                <div class="col-md-6">
                    <label for="apellidos" class="form-label fw-semibold text-dark">Apellidos</label>
                    <input type="text" class="form-control @error('apellidos') is-invalid @enderror" id="apellidos" name="apellidos" value="{{ old('apellidos', $patient->apellidos) }}" required>
                    @error('apellidos') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Teléfono -->
                <div class="col-md-6">
                    <label for="telefono" class="form-label fw-semibold text-dark">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $patient->telefono) }}" required>
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold text-dark">Correo electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $patient->email) }}" placeholder="ejemplo@correo.com">
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <!-- Fecha Nacimiento -->
                <div class="col-md-3">
                    <label for="fecha_nacimiento" class="form-label fw-semibold text-dark">Fecha Nacimiento</label>
                    <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $patient->fecha_nacimiento) }}" required>
                    @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>



                <!-- Áreas de texto -->
                <div class="col-md-12">
                    <label for="alergias" class="form-label fw-semibold text-dark">Alergias</label>
                    <textarea class="form-control" id="alergias" name="alergias" rows="2">{{ old('alergias', $patient->alergias) }}</textarea>
                </div>
                <div class="col-md-12">
                    <label for="enfermedades" class="form-label fw-semibold text-dark">Enfermedades</label>
                    <textarea class="form-control" id="enfermedades" name="enfermedades" rows="2">{{ old('enfermedades', $patient->enfermedades) }}</textarea>
                </div>
                <div class="col-md-12">
                    <label for="tratamientos" class="form-label fw-semibold text-dark">Tratamientos</label>
                    <textarea class="form-control" id="tratamientos" name="tratamientos" rows="2">{{ old('tratamientos', $patient->tratamientos) }}</textarea>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('patients.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">Cancelar</a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-save me-2"></i> Actualizar Datos
                </button>
            </div>
        </form>
    </div>
</x-plantilla>