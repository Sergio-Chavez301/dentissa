<x-plantilla>
    <x-slot:title>Nueva Cita | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Agendar Nueva Cita</h1>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 700px;">
        <form action="{{ route('citas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" name="nuevo_paciente" id="checkNuevo" onchange="togglePaciente(this)">
                    <label class="form-check-label fw-bold" for="checkNuevo">Registrar nuevo paciente para esta cita</label>
                </div>

                <div id="selectorPaciente">
                    <label class="form-label fw-semibold">Paciente Existente</label>
                    <select name="paciente_id" id="paciente_id" class="form-select">
                        <option value="">Seleccione un paciente...</option>
                        @foreach($pacientes as $paciente)
                            <option value="{{ $paciente->id }}">{{ $paciente->nombre }} {{ $paciente->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="formNuevoPaciente" class="p-3 bg-light rounded border mt-2" style="display: none;">
                    <h6 class="fw-bold mb-3">Datos del Nuevo Paciente</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control" disabled></div>
                        <div class="col-md-6"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control" disabled></div>
                        <div class="col-md-6"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control" disabled></div>
                        <div class="col-md-6"><label class="form-label">Fecha Nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control" disabled></div>
                        <div class="col-md-12"><label class="form-label">Alergias</label><textarea name="alergias" class="form-control" rows="2" disabled></textarea></div>
                        <div class="col-md-12"><label class="form-label">Enfermedades Crónicas</label><textarea name="enfermedades" class="form-control" rows="2" disabled></textarea></div>
                        <div class="col-md-12"><label class="form-label">Tratamientos Actuales</label><textarea name="tratamientos" class="form-control" rows="2" disabled></textarea></div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" onchange="cargarHorarios()" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Hora disponible</label>
                    <select id="hora" name="hora" class="form-select" required>
                        <option value="">Seleccione una fecha primero...</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Servicio</label>
                <select name="servicio_id" class="form-select" required>
                    <option value="">Seleccione un tratamiento...</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}">{{ $servicio->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('citas.index') }}" class="btn btn-light border">Cancelar</a>
                <button type="submit" class="btn text-white bg-dentissa px-4">Agendar</button>
            </div>
        </form>
    </div>

    <script>
        function togglePaciente(checkbox) {
            const selectorDiv = document.getElementById('selectorPaciente');
            const formNuevo = document.getElementById('formNuevoPaciente');
            const selectPaciente = document.getElementById('paciente_id');
            const inputs = formNuevo.querySelectorAll('input, textarea');

            if (checkbox.checked) {
                selectorDiv.style.display = 'none';
                selectPaciente.disabled = true;
                formNuevo.style.display = 'block';
                inputs.forEach(i => { i.disabled = false; });
            } else {
                selectorDiv.style.display = 'block';
                selectPaciente.disabled = false;
                formNuevo.style.display = 'none';
                inputs.forEach(i => { i.disabled = true; });
            }
        }

        function cargarHorarios() {
            let fecha = document.getElementById('fecha').value;
            let selectHora = document.getElementById('hora');
            if (!fecha) return;
            selectHora.innerHTML = '<option>Cargando...</option>';

            fetch(`/api/disponibilidad?fecha=${fecha}`)
                .then(r => r.json())
                .then(data => {
                    selectHora.innerHTML = '<option value="">Seleccione una hora</option>';
                    if(data.horarios.length > 0) {
                        data.horarios.forEach(h => {
                            selectHora.innerHTML += `<option value="${h}">${h.substring(0, 5)}</option>`;
                        });
                    } else {
                        selectHora.innerHTML = '<option value="">No hay disponibilidad</option>';
                    }
                });
        }
    </script>
</x-plantilla>