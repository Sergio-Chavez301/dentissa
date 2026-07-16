<x-plantilla>
    <x-slot:title>Panel de Administración | Melissa López N.</x-slot:title>

    <!-- Encabezado de Bienvenida -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Bienvenido de nuevo, {{ Auth::user()->nombre }}</h1>
            <p class="text-muted">Gestión clínica y administrativa de Odontología Integral</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-dentissa py-2 px-3 fs-6 rounded-pill shadow-sm">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <!-- Componente de Datos Rápidos -->
    <x-datos-rapidos citas="8" pacientes="210" casos="15" servicios="12" />

    <div class="row g-4">
        <!-- SECCIÓN IZQUIERDA: Citas y Casos Clínicos -->
        <div class="col-lg-8">
            
            <!-- Tabla 1: Citas del Día -->
            <div class="card p-4 shadow-sm bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1e1e24;">
                        <i class="bi bi-clock-history text-dentissa me-2"></i>Próximas Citas de Hoy
                    </h5>
                    <button class="btn btn-sm text-dentissa fw-semibold border-0">Ver todas</button>
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
                            <tr>
                                <td class="fw-semibold">09:00 AM</td>
                                <td>Sergio Chávez</td>
                                <td>Endodoncia molar</td>
                                <td><span class="badge rounded-pill bg-success-subtle text-success px-2.5 py-1">Confirmada</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">11:30 AM</td>
                                <td>Beatriz Domínguez</td>
                                <td>Limpieza Dental Profunda</td>
                                <td><span class="badge rounded-pill bg-warning-subtle text-warning px-2.5 py-1">Pendiente</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla 2: Seguimiento de Casos Clínicos Activos -->
            <div class="card p-4 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1e1e24;">
                        <i class="bi bi-file-earmark-medical text-dentissa me-2"></i>Casos Clínicos Activos
                    </h5>
                    <button class="btn btn-sm text-dentissa fw-semibold border-0">Ver expedientes</button>
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
                            <tr>
                                <td class="text-muted">#102</td>
                                <td class="fw-semibold">Carlos Mendoza</td>
                                <td>Ortodoncia Correctiva</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100 me-2" style="height: 6px;">
                                            <div class="progress-bar bg-dentissa" role="progressbar" style="width: 60%"></div>
                                        </div>
                                        <span class="small text-muted">60%</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">#105</td>
                                <td class="fw-semibold">Liliana Torres</td>
                                <td>Implante Dental Pre-molar</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress w-100 me-2" style="height: 6px;">
                                            <div class="progress-bar bg-dentissa" role="progressbar" style="width: 30%"></div>
                                        </div>
                                        <span class="small text-muted">30%</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- SECCIÓN DERECHA: Acciones rápidas y Pacientes Nuevos -->
        <div class="col-lg-4">
            
            <!-- Bloque de Acciones Rápidas -->
            <div class="card p-4 shadow-sm bg-white mb-4">
                <h5 class="fw-bold mb-3" style="color: #1e1e24;">Acciones Rápidas</h5>
                <div class="d-grid gap-2">
                    <button class="btn text-white border-0 py-2.5 fw-semibold bg-dentissa shadow-sm">
                        <i class="bi bi-calendar-plus me-2"></i> Nueva Cita
                    </button>
                    <button class="btn btn-outline-secondary py-2.5 fw-semibold" style="color: #1e1e24; border-color: #e0e0e0;">
                        <i class="bi bi-person-plus-fill me-2 text-dentissa"></i> Registrar Paciente
                    </button>
                    <button class="btn btn-outline-secondary py-2.5 fw-semibold" style="color: #1e1e24; border-color: #e0e0e0;">
                        <i class="bi bi-folder-plus me-2 text-dentissa"></i> Nuevo Caso Clínico
                    </button>
                </div>
            </div>

            <!-- Tabla 3: Últimos Pacientes Registrados -->
            <div class="card p-4 shadow-sm bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0" style="color: #1e1e24;">Últimos Pacientes</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                        <div>
                            <div class="fw-semibold" style="color: #1e1e24;">Diana Valenzuela</div>
                            <small class="text-muted">Registrado: Hoy 4:20 PM</small>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark border">Ver</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                        <div>
                            <div class="fw-semibold" style="color: #1e1e24;">Roberto Ruiz</div>
                            <small class="text-muted">Registrado: Ayer</small>
                        </div>
                        <span class="badge rounded-pill bg-light text-dark border">Ver</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</x-plantilla>