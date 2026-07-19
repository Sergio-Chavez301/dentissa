<x-plantilla>
    <x-slot:title>Registrar Caso | Dentissa</x-slot:title>

    <!-- Encabezado igual al de registro de pacientes -->
    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('casos.index') }}" class="text-decoration-none text-muted small d-inline-flex align-items-center mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Registrar Nuevo Caso</h1>
        <p class="text-muted">Asigna un tratamiento base y progreso al paciente.</p>
    </div>

    <!-- Tarjeta con estilos consistentes -->
    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 800px;">
        <form action="{{ route('casos.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Información del Caso -->
                <div class="col-md-8">
                    <label for="tratamiento_base" class="form-label fw-semibold text-dark">Tratamiento Base</label>
                    <input type="text" class="form-control" id="tratamiento_base" name="tratamiento_base" required>
                </div>
                <div class="col-md-4">
                    <label for="progreso" class="form-label fw-semibold text-dark">Progreso Inicial (%)</label>
                    <input type="number" class="form-control" id="progreso" name="progreso" value="0" min="0" max="100" required>
                </div>

                <!-- Selector de Paciente -->
                <div class="col-md-12 mt-3">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="nuevo_paciente" id="checkNuevo" onchange="toggleForm(this)">
                        <label class="form-check-label fw-bold" for="checkNuevo">Registrar nuevo paciente en este caso</label>
                    </div>

                    <div id="selectorPaciente">
                        <label for="paciente_id" class="form-label fw-semibold text-dark">Paciente</label>
                        <select name="paciente_id" id="paciente_id" class="form-select">
                            <option value="">Seleccione un paciente existente...</option>
                            @foreach($pacientes as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }} {{ $p->apellidos }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Formulario Completo de Nuevo Paciente -->
                <div id="formCompletoPaciente" class="col-md-12 p-3 bg-light rounded border mt-2" style="display: none;">
                    <h6 class="fw-bold mb-3 text-dark">Datos del Nuevo Paciente</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nombre</label><input type="text" name="nombre" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Apellidos</label><input type="text" name="apellidos" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Teléfono</label><input type="text" name="telefono" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Fecha Nacimiento</label><input type="date" name="fecha_nacimiento" class="form-control"></div>
                        <div class="col-md-12"><label class="form-label">Alergias</label><textarea name="alergias" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-12"><label class="form-label">Enfermedades Crónicas</label><textarea name="enfermedades" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-12"><label class="form-label">Tratamientos Actuales</label><textarea name="tratamientos" class="form-control" rows="2"></textarea></div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción iguales al registro de paciente -->
            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="{{ route('casos.index') }}" class="btn btn-light border py-2 px-4 fw-semibold text-muted">
                    Cancelar
                </a>
                <button type="submit" class="btn text-white border-0 py-2 px-4 fw-semibold shadow-sm bg-dentissa">
                    <i class="bi bi-save me-2"></i> Guardar Caso
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleForm(checkbox) {
            const selectorDiv = document.getElementById('selectorPaciente');
            const selectElement = document.getElementById('paciente_id');
            const formNuevoDiv = document.getElementById('formCompletoPaciente');
            const inputsNuevo = formNuevoDiv.querySelectorAll('input, textarea');

            if (checkbox.checked) {
                selectorDiv.style.display = 'none';
                selectElement.disabled = true;
                formNuevoDiv.style.display = 'block';
                inputsNuevo.forEach(input => input.disabled = false);
            } else {
                selectorDiv.style.display = 'block';
                selectElement.disabled = false;
                formNuevoDiv.style.display = 'none';
                inputsNuevo.forEach(input => input.disabled = true);
            }
        }
        // Inicializar al cargar
        window.onload = () => toggleForm(document.getElementById('checkNuevo'));
    </script>
</x-plantilla>