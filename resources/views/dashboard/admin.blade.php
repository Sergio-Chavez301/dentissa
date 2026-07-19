<x-plantilla>
    <x-slot:title>Panel de Administración | Melissa López N.</x-slot:title>

    <style>
        .btn-rosa-hover {
            transition: all 0.3s ease;
        }
        .btn-rosa-hover:hover {
            background-color: #ff80ab !important;
            border-color: #ff80ab !important;
            color: white !important;
        }
        .btn-rosa-hover:active {
            background-color: #f50057 !important;
            border-color: #f50057 !important;
        }
    </style>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Bienvenido de nuevo, {{ Auth::user()->nombre ?? 'Usuario' }}</h1>
            <p class="text-muted">Gestión clínica y administrativa de Odontología Integral</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-dentissa py-2 px-3 fs-6 rounded-pill shadow-sm text-white">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <x-datos-rapidos 
        :citas="$citasHoyCount" 
        :pacientes="$pacientesCount" 
        :casos="$casosActivosCount" 
        :servicios="$serviciosCount" 
    />

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 shadow-sm border-0 bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1e1e24;">
                        <i class="bi bi-clock-history text-dentissa me-2"></i>Próximas Citas de Hoy
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr class="text-muted" style="font-size: 0.85rem;">
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Tratamiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($citasHoy as $cita)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</td>
                                    <!-- CORREGIDO: $cita->paciente en lugar de $cita->user -->
                                    <td>{{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellidos ?? '' }}</td>
                                    <td>{{ $cita->servicio->nombre ?? 'Tratamiento General' }}</td>
                                    <td>
                                        @if(strtolower($cita->estado ?? '') === 'confirmada')
                                            <span class="badge rounded-pill bg-success-subtle text-success px-2.5 py-1">Confirmada</span>
                                        @elseif(strtolower($cita->estado ?? '') === 'pendiente')
                                            <span class="badge rounded-pill bg-warning-subtle text-warning px-2.5 py-1">Pendiente</span>
                                        @else
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2.5 py-1">{{ ucfirst($cita->estado ?? 'Pendiente') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No hay citas para hoy.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card p-4 shadow-sm border-0 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1e1e24;">
                        <i class="bi bi-file-earmark-medical text-dentissa me-2"></i>Casos Clínicos Activos
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr class="text-muted" style="font-size: 0.85rem;">
                                <th>ID</th>
                                <th>Paciente</th>
                                <th>Tratamiento Base</th>
                                <th>Progreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($casosActivos as $caso)
                                <tr>
                                    <td class="text-muted">#{{ $caso->id }}</td>
                                    <!-- CORREGIDO: $caso->paciente en lugar de $caso->user -->
                                    <td class="fw-semibold">{{ $caso->paciente->nombre ?? 'Desconocido' }} {{ $caso->paciente->apellidos ?? '' }}</td>
                                    <td>{{ $caso->tratamiento_base ?? 'No especificado' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress w-100 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-dentissa" role="progressbar" style="width: {{ $caso->progreso ?? 0 }}%"></div>
                                            </div>
                                            <span class="small text-muted">{{ $caso->progreso ?? 0 }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">No hay casos activos.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 shadow-sm border-0 bg-white mb-4">
                <h5 class="fw-bold mb-3" style="color: #1e1e24;">Acciones Rápidas</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('citas.create')}}" class="btn btn-outline-secondary py-2.5 btn-rosa-hover">
                        <i class="bi bi-calendar-plus me-2"></i>Nueva Cita
                    </a>
                    <a href="{{ route('patients.create') }}" class="btn btn-outline-secondary py-2.5 btn-rosa-hover">
                        <i class="bi bi-person-plus me-2"></i>Registrar Paciente
                    </a>
                    <a href="{{ route('casos.create')}}" class="btn btn-outline-secondary py-2.5 btn-rosa-hover">
                        <i class="bi bi-clipboard-plus me-2"></i>Nuevo Caso Clínico
                    </a>
                </div>
            </div>

            <div class="card p-4 shadow-sm border-0 bg-white">
                <h5 class="fw-bold mb-3" style="color: #1e1e24;">Últimos Pacientes</h5>
                <ul class="list-group list-group-flush">
                    @forelse($ultimosPacientes as $paciente)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <div>
                                <div class="fw-semibold">{{ $paciente->nombre }} {{ $paciente->apellidos ?? '' }}</div>
                                <small class="text-muted">{{ $paciente->created_at->diffForHumans() }}</small>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item px-0 bg-transparent text-center text-muted py-3">No hay pacientes registrados.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-plantilla>