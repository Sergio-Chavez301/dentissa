<x-plantilla>
    <x-slot:title>Nueva Cita | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Agendar Nueva Cita</h1>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 700px;">
        <form action="{{ route('citas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Paciente</label>
                <select name="paciente_id" class="form-select @error('paciente_id') is-invalid @enderror" required>
                    <option value="">Seleccione un paciente...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}" {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}>
                            {{ $paciente->nombre }} {{ $paciente->apellidos }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" name="fecha" class="form-control @error('fecha') is-invalid @enderror" value="{{ old('fecha') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Hora</label>
                    <input type="time" name="hora" class="form-control @error('hora') is-invalid @enderror" value="{{ old('hora') }}" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Servicio</label>
                <select name="servicio_id" class="form-select @error('servicio_id') is-invalid @enderror" required>
                    <option value="">Seleccione un tratamiento...</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('citas.index') }}" class="btn btn-light border">Cancelar</a>
                <button type="submit" class="btn text-white bg-dentissa px-4">Agendar</button>
            </div>
        </form>
    </div>
</x-plantilla>