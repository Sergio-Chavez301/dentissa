<x-plantilla>
    <x-slot:title>Panel de asistente</x-slot:title>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Panel de asistente</h1>
            <p class="text-muted">Coordina pacientes, citas y seguimiento clínico.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-dentissa py-2 px-3 fs-6 rounded-pill shadow-sm text-white">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-dentissa me-2"></i>Próximas citas</h5>
                    <a href="{{ route('citas.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr class="text-muted">
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($citas = App\Models\Cita::with(['paciente','servicio'])->whereDate('fecha', now()->toDateString())->orderBy('hora')->take(5)->get())
                            @forelse($citas as $cita)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</td>
                                    <td>{{ $cita->paciente->nombre ?? 'Sin paciente' }} {{ $cita->paciente->apellidos ?? '' }}</td>
                                    <td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td>
                                    <td><span class="badge rounded-pill bg-warning-subtle text-warning">{{ ucfirst($cita->estado) }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">No hay citas para hoy.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow-sm border-0 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-medical text-dentissa me-2"></i>Casos clínicos activos</h5>
                    <a href="{{ route('casos.index') }}" class="btn btn-sm btn-outline-secondary">Ver todos</a>
                </div>
                <ul class="list-group list-group-flush">
                    @php($casos = App\Models\CasoClinico::with('paciente')->where('estado', 'activo')->latest()->take(5)->get())
                    @forelse($casos as $caso)
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $caso->tratamiento_base }}</div>
                                    <small class="text-muted">{{ $caso->paciente->nombre ?? 'Paciente' }} {{ $caso->paciente->apellidos ?? '' }}</small>
                                </div>
                                <span class="badge rounded-pill bg-success-subtle text-success">{{ $caso->progreso }}%</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">No hay casos activos por el momento.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-person-plus text-dentissa me-2"></i>Acciones rápidas</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('patients.create') }}" class="btn btn-outline-secondary">Registrar paciente</a>
                    <a href="{{ route('citas.create') }}" class="btn btn-outline-secondary">Agendar cita</a>
                    <a href="{{ route('casos.create') }}" class="btn btn-outline-secondary">Nuevo caso clínico</a>
                </div>
            </div>
        </div>
    </div>
</x-plantilla>