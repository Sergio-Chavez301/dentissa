<x-plantilla>
    <x-slot:title>Detalle de Solicitud | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('solicitudes.index') }}" class="text-muted text-decoration-none small"><i class="bi bi-arrow-left"></i> Volver</a>
        <h1 class="h2 fw-bold">Gestionar Solicitud de {{ $solicitud->nombre }}</h1>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 700px;">
        <div class="mb-4">
            <p><strong>Paciente:</strong> {{ $solicitud->nombre }} {{ $solicitud->apellidos }}</p>
            <p><strong>Contacto:</strong> {{ $solicitud->email }} | {{ $solicitud->telefono }}</p>
            <p><strong>Motivo:</strong> {{ $solicitud->motivo_consulta }}</p>
            <p><strong>Fecha sugerida:</strong> {{ \Carbon\Carbon::parse($solicitud->fecha_hora_propuesta)->format('d/m/Y H:i') }}</p>
        </div>

        <form action="{{ route('solicitudes.update', $solicitud->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Asignar Paciente -->
            <div class="mb-3">
                <label class="form-label fw-bold">Asignar a paciente registrado:</label>
                <select name="paciente_id" class="form-select">
                    <option value="">-- Crear nuevo paciente automáticamente --</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}">{{ $paciente->nombre }} {{ $paciente->apellidos }}</option>
                    @endforeach
                </select>
                <small class="text-muted">Si no seleccionas ninguno, se registrará al paciente nuevo.</small>
            </div>

            <!-- Fecha de nacimiento -->
            <div class="mb-3">
                <label class="form-label fw-bold">Fecha de nacimiento:</label>
                <input type="date" name="fecha_nacimiento" class="form-control" 
                       value="{{ $solicitud->fecha_nacimiento ?? '' }}" required>
            </div>

            <!-- Servicio -->
            <div class="mb-3">
                <label class="form-label fw-bold">Servicio a realizar:</label>
                <select name="servicio_id" class="form-select" required>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="confirmar" value="1" class="btn bg-dentissa text-white fw-bold">
                    <i class="bi bi-check-lg"></i> Confirmar y Agendar
                </button>
                
                <button type="submit" name="estado" value="rechazada" class="btn btn-outline-danger">
                    <i class="bi bi-x-lg"></i> Rechazar
                </button>
            </div>
        </form>
    </div>
</x-plantilla>