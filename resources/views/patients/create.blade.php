<x-plantilla>
    <x-slot:title>Registrar Paciente | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('patients.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Registrar Nuevo Paciente</h1>
        <p class="text-muted">Ingresa los datos personales y el historial médico del nuevo paciente.</p>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('patients.store') }}" method="POST">
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

                <!-- Teléfono y Edad -->
                <div class="col-md-6">
                    <label for="telefono" class="form-label fw-semibold text-dark">Teléfono</label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}" required>
                    @error('telefono')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold text-dark">Correo electrónico</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="fecha_nacimiento" class="form-label fw-semibold text-dark">Fecha Nacimiento</label>
                    <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                </div>


                <!-- Antecedentes -->
                <div class="col-md-12">
                    <label for="alergias" class="form-label fw-semibold text-dark">Alergias</label>
                    <textarea class="form-control" id="alergias" name="alergias" rows="2">{{ old('alergias') }}</textarea>
                </div>

                <div class="col-md-12">
                    <label for="enfermedades" class="form-label fw-semibold text-dark">Enfermedades Crónicas</label>
                    <textarea class="form-control" id="enfermedades" name="enfermedades" rows="2">{{ old('enfermedades') }}</textarea>
                </div>

                <div class="col-md-12">
                    <label for="tratamientos" class="form-label fw-semibold text-dark">Tratamientos Actuales</label>
                    <textarea class="form-control" id="tratamientos" name="tratamientos" rows="2">{{ old('tratamientos') }}</textarea>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('patients.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-save me-2"></i> Guardar Paciente
                </button>
            </div>
        </form>
    </div>
</x-plantilla>