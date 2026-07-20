<x-plantilla>
    <x-slot:title>Editar Cita | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('citas.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Editar Cita #{{ $cita->id }}</h1>
    </div>

    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('citas.update', $cita->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark">Paciente</label>
                    <input type="text" class="form-control bg-light" value="{{ $cita->paciente->nombre }} {{ $cita->paciente->apellidos }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark">Servicio</label>
                    <input type="text" class="form-control bg-light" value="{{ $cita->servicio->nombre }}" disabled>
                </div>

                <!-- Fecha con onchange para cargar horarios -->
                <div class="col-md-6">
                    <label for="fecha" class="form-label fw-semibold text-dark">Fecha</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', $cita->fecha) }}" onchange="cargarHorarios()" required>
                </div>

                <!-- Hora como select para cargar dinámicamente -->
                <div class="col-md-6">
                    <label for="hora" class="form-label fw-semibold text-dark">Hora</label>
                    <select id="hora" name="hora" class="form-select" required>
                        <option value="{{ $cita->hora }}">{{ substr($cita->hora, 0, 5) }} (Actual)</option>
                    </select>
                </div>

                <div class="col-md-12 mt-3">
                    <label for="estado" class="form-label fw-semibold text-dark">Estado de la Cita</label>
                    <select class="form-select" id="estado" name="estado" required>
                        <option value="{{ \App\Models\Cita::ESTADO_EN_ESPERA }}" {{ old('estado', $cita->estado) == \App\Models\Cita::ESTADO_EN_ESPERA ? 'selected' : '' }}>En Espera</option>
                        <option value="{{ \App\Models\Cita::ESTADO_REALIZADA }}" {{ old('estado', $cita->estado) == \App\Models\Cita::ESTADO_REALIZADA ? 'selected' : '' }}>Realizada</option>
                        <option value="{{ \App\Models\Cita::ESTADO_NO_PRESENTO }}" {{ old('estado', $cita->estado) == \App\Models\Cita::ESTADO_NO_PRESENTO ? 'selected' : '' }}>No se presentó</option>
                        <option value="{{ \App\Models\Cita::ESTADO_CANCELADA }}" {{ old('estado', $cita->estado) == \App\Models\Cita::ESTADO_CANCELADA ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('citas.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">Cancelar</a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-arrow-repeat me-2"></i> Actualizar Cita
                </button>
            </div>
        </form>
    </div>

    <script>
        function cargarHorarios() {
            let fecha = document.getElementById('fecha').value;
            let selectHora = document.getElementById('hora');
            
            if (!fecha) return;
            selectHora.innerHTML = '<option>Cargando horarios...</option>';

            fetch(`{{ route('api.disponibilidad') }}?fecha=${fecha}`)
                .then(response => response.json())
                .then(data => {
                    selectHora.innerHTML = '<option value="">Seleccione una hora</option>';
                    if(data.horarios && data.horarios.length > 0) {
                        data.horarios.forEach(h => {
                            let isSelected = (h === "{{ $cita->hora }}" || h === "{{ substr($cita->hora, 0, 5) }}") ? 'selected' : '';
                            selectHora.innerHTML += `<option value="${h}" ${isSelected}>${h.substring(0, 5)}</option>`;
                        });
                    } else {
                        selectHora.innerHTML = '<option value="">No hay disponibilidad</option>';
                    }
                });
        }

        // Cargar los horarios apenas cargue la página con la fecha actual
        document.addEventListener('DOMContentLoaded', cargarHorarios);
    </script>
</x-plantilla>