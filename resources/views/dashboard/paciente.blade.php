<x-plantilla>
    <x-slot:title>Mi Panel | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <h1 class="h2 fw-bold" style="color: #1e1e24;">Bienvenido(a), {{ $patient->nombre }} {{ $patient->apellidos }}</h1>
        <p class="text-muted">Aquí puedes consultar el estado de tus citas dentales y tu historial clínico.</p>
    </div>

    <div class="row g-4">
        <!-- Tarjeta de Información Personal -->
        <div class="col-md-4">
            <div class="card p-4 shadow-sm border-0 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge text-dentissa me-2"></i> Mis Datos</h5>
                <p class="mb-1 text-muted small">Correo Electrónico:</p>
                <p class="fw-medium text-dark">{{ $patient->email ?? 'No registrado' }}</p>

                <p class="mb-1 text-muted small">Teléfono:</p>
                <p class="fw-medium text-dark">{{ $patient->telefono ?? 'No registrado' }}</p>

                <p class="mb-1 text-muted small">Fecha de Nacimiento:</p>
                <p class="fw-medium text-dark">{{ $patient->fecha_nacimiento ?? 'No registrada' }}</p>
            </div>
        </div>

        <!-- Tarjeta de Próximas Citas -->
        <div class="col-md-8">
            <div class="card p-4 shadow-sm border-0 bg-white mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-calendar-check text-dentissa me-2"></i> Mis Citas</h5>
                @if($patient->citas && $patient->citas->count() > 0)
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted" style="font-size: 0.85rem;">
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->citas as $cita)
                                    <tr>
                                        <td class="fw-semibold">{{ $cita->servicio->nombre ?? 'Consulta General' }}</td>
                                        <td>{{ $cita->fecha }}</td>
                                        <td>{{ $cita->hora }}</td>
                                        <td>
                                            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1">
                                                {{ ucfirst($cita->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No tienes citas registradas en este momento.</p>
                @endif
            </div>

            <!-- Tarjeta de Historial / Casos Clínicos -->
            <div class="card p-4 shadow-sm border-0 bg-white">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-medical text-dentissa me-2"></i> Mis Casos Clínicos y Tratamientos</h5>
                @if($patient->casosClinicos && $patient->casosClinicos->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($patient->casosClinicos as $caso)
                            <li class="list-group-item px-0">
                                <span class="fw-semibold text-dark">{{ $caso->titulo ?? 'Tratamiento Dental' }}</span>
                                <p class="text-muted small mb-0">{{ $caso->descripcion ?? 'Sin descripción adicional.' }}</p>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No hay registros clínicos activos en tu historial.</p>
                @endif
            </div>
        </div>
    </div>
</x-plantilla>