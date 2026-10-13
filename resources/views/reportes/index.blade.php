<x-plantilla>
    <x-slot:title>Reportes | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold text-dark">Reportes del sistema</h1>
            <p class="text-muted mb-0">Resumen operativo y seguimiento clínico de Dentissa.</p>
        </div>
        <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary">Volver al panel</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Citas</div>
                    <div class="display-6 fw-bold text-dark mt-2">{{ $totalCitas }}</div>
                    <div class="text-muted small">Total del sistema</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Hoy</div>
                    <div class="display-6 fw-bold text-dark mt-2">{{ $citasHoy }}</div>
                    <div class="text-muted small">Citas agendadas</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Pacientes</div>
                    <div class="display-6 fw-bold text-dark mt-2">{{ $pacientesActivos }}</div>
                    <div class="text-muted small">Registrados</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold">Estimado</div>
                    <div class="display-6 fw-bold text-dark mt-2">$ {{ number_format($ingresosEstimados, 0) }}</div>
                    <div class="text-muted small">Ingreso por servicios</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-bar-chart me-2 text-dentissa"></i>Estado de citas</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th>Estado</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>En espera</td>
                                    <td><span class="badge bg-warning-subtle text-warning">{{ $citasPorEstado['en_espera'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Realizadas</td>
                                    <td><span class="badge bg-success-subtle text-success">{{ $citasPorEstado['realizada'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>Canceladas</td>
                                    <td><span class="badge bg-secondary-subtle text-secondary">{{ $citasPorEstado['cancelada'] }}</span></td>
                                </tr>
                                <tr>
                                    <td>No presento</td>
                                    <td><span class="badge bg-danger-subtle text-danger">{{ $citasPorEstado['no_presento'] }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-file-earmark-medical me-2 text-dentissa"></i>Casos y servicios</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Casos activos</span>
                        <span class="fw-bold text-dark">{{ $casosActivos }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Servicios</span>
                        <span class="fw-bold text-dark">{{ $serviciosActivos }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Promedio por cita</span>
                        <span class="fw-bold text-dark">$ {{ $totalCitas ? number_format($ingresosEstimados / $totalCitas, 0) : 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white border-0 pt-4">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-dentissa"></i>Últimas citas</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Paciente</th>
                            <th>Servicio</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ultimasCitas as $cita)
                            <tr>
                                <td>{{ $cita->paciente->nombre ?? 'Sin dato' }} {{ $cita->paciente->apellidos ?? '' }}</td>
                                <td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td>
                                <td>{{ $cita->fecha }}</td>
                                <td>
                                    <span class="badge {{ $cita->estado === 'realizada' ? 'bg-success-subtle text-success' : ($cita->estado === 'cancelada' ? 'bg-secondary-subtle text-secondary' : 'bg-warning-subtle text-warning') }}">
                                        {{ ucfirst(str_replace('_', ' ', $cita->estado)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">Aún no hay citas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-plantilla>
