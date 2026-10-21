<x-plantilla>
    <x-slot:title>Mi Panel | Dentissa</x-slot:title>

    @php
        $citasPorEstado = $patient->citas->countBy('estado');
        $estadosCita = [
            'en_espera' => ['En espera', 'text-bg-warning'],
            'confirmada' => ['Confirmadas', 'text-bg-success'],
            'cancelada' => ['Canceladas', 'text-bg-secondary'],
            'no_presento' => ['No se presentó', 'text-bg-danger'],
            'realizada' => ['Realizadas', 'text-bg-info'],
        ];
    @endphp

    <header class="mb-4 border-bottom pb-3">
        <span class="text-muted small text-uppercase fw-bold">Portal del paciente</span>
        <h1 class="h2 fw-bold mt-1 mb-2" style="color: #1e1e24;">Hola, {{ $patient->nombre }}</h1>
        <p class="text-muted mb-0">Consulta tus citas, tratamientos y datos de perfil en un solo lugar.</p>
    </header>

    <section class="row g-3 mb-4" aria-label="Resumen de citas">
        @foreach($estadosCita as $estado => [$etiqueta, $color])
            <div class="col-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">{{ $etiqueta }}</div>
                        <div class="fs-3 fw-bold text-dark">{{ $citasPorEstado->get($estado, 0) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </section>

    <section class="card border-0 shadow-sm mb-4" aria-labelledby="perfil-heading">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                <div>
                    <span class="text-muted small text-uppercase fw-bold">Tu cuenta</span>
                    <h2 id="perfil-heading" class="h5 fw-bold mb-0 mt-1">Perfil personal</h2>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-secondary">Editar perfil y contraseña</a>
            </div>
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Nombre completo</div>
                    <div class="fw-semibold">{{ $patient->nombre }} {{ $patient->apellidos }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Usuario</div>
                    <div class="fw-semibold">{{ $patient->user?->username ?? 'No disponible' }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Correo</div>
                    <div class="fw-semibold">{{ $patient->email ?? $patient->user?->email ?? 'No registrado' }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Teléfono</div>
                    <div class="fw-semibold">{{ $patient->telefono ?? 'No registrado' }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Fecha de nacimiento</div>
                    <div class="fw-semibold">{{ $patient->fecha_nacimiento ?? 'No registrada' }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Alergias</div>
                    <div class="fw-semibold">{{ $patient->alergias ?: 'Sin registro' }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Enfermedades</div>
                    <div class="fw-semibold">{{ $patient->enfermedades ?: 'Sin registro' }}</div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="small text-muted">Tratamientos actuales</div>
                    <div class="fw-semibold">{{ $patient->tratamientos ?: 'Sin registro' }}</div>
                </div>
            </div>
        </div>
    </section>

    <section class="card border-0 shadow-sm mb-4" aria-labelledby="citas-heading">
        <div class="card-body p-4">
            <div class="mb-3">
                <span class="text-muted small text-uppercase fw-bold">Agenda</span>
                <h2 id="citas-heading" class="h5 fw-bold mb-0 mt-1">Mis citas</h2>
            </div>
            @if($patient->citas->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr class="text-muted small">
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Tratamiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->citas->sortByDesc(fn ($cita) => $cita->fecha . ' ' . $cita->hora) as $cita)
                                @php($estadoCita = $estadosCita[$cita->estado] ?? [ucfirst(str_replace('_', ' ', $cita->estado)), 'text-bg-secondary'])
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ substr($cita->hora, 0, 5) }}</td>
                                    <td>{{ $cita->servicio?->nombre ?? 'Consulta general' }}</td>
                                    <td>{{ $cita->casoClinico?->tratamiento_base ?? 'Sin tratamiento asociado' }}</td>
                                    <td><span class="badge {{ $estadoCita[1] }}">{{ $estadoCita[0] }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">Aún no tienes citas registradas.</p>
            @endif
        </div>
    </section>

    <section class="mb-4" aria-labelledby="tratamientos-heading">
        <div class="mb-3">
            <span class="text-muted small text-uppercase fw-bold">Seguimiento clínico</span>
            <h2 id="tratamientos-heading" class="h5 fw-bold mb-0 mt-1">Mis tratamientos</h2>
        </div>
        @forelse($patient->casosClinicos as $caso)
            @php($progreso = min(100, max(0, (int) $caso->progreso)))
            <article class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <h3 class="h6 fw-bold mb-0">{{ $caso->tratamiento_base ?? 'Tratamiento dental' }}</h3>
                        <span class="badge {{ $caso->estado === 'activo' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($caso->estado) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Progreso del tratamiento</span>
                        <span class="fw-semibold">{{ $progreso }}%</span>
                    </div>
                    <div class="progress mb-3" role="progressbar" aria-label="Progreso del tratamiento" aria-valuenow="{{ $progreso }}" aria-valuemin="0" aria-valuemax="100" style="height: 8px;">
                        <div class="progress-bar bg-dentissa" style="width: {{ $progreso }}%"></div>
                    </div>

                    <h4 class="small fw-bold text-muted text-uppercase mb-2">Citas realizadas para este tratamiento</h4>
                    @php($citasRealizadas = $caso->citas->where('estado', \App\Models\Cita::ESTADO_REALIZADA)->sortByDesc(fn ($cita) => $cita->fecha . ' ' . $cita->hora))
                    @if($citasRealizadas->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($citasRealizadas as $citaRealizada)
                                <li class="list-group-item px-0 d-flex justify-content-between gap-3">
                                    <span>{{ $citaRealizada->servicio?->nombre ?? 'Consulta general' }}</span>
                                    <span class="text-muted text-nowrap">{{ \Illuminate\Support\Carbon::parse($citaRealizada->fecha)->format('d/m/Y') }} · {{ substr($citaRealizada->hora, 0, 5) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted small mb-0">Todavía no hay citas realizadas asociadas a este tratamiento.</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-muted">Aún no hay tratamientos registrados en tu expediente.</div>
            </div>
        @endforelse
    </section>

    <section class="mb-4" aria-labelledby="ofertas-heading">
        <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
            <div>
                <span class="text-muted small text-uppercase fw-bold">Para ti</span>
                <h2 id="ofertas-heading" class="h5 fw-bold mb-0 mt-1">Ofertas activas</h2>
            </div>
            <a href="{{ route('ofertas.public') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
        </div>
        <div class="row g-3">
            @forelse($ofertas as $oferta)
                <div class="col-md-4">
                    <article class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <span class="small text-dentissa fw-bold">{{ strtoupper($oferta->tag ?? 'PROMOCIÓN') }}</span>
                            <h3 class="h6 fw-bold mt-2">{{ $oferta->titulo }}</h3>
                            <p class="small text-muted mb-2">{{ $oferta->descripcion }}</p>
                            @if($oferta->precio)
                                <div class="fw-bold">${{ number_format((float) $oferta->precio, 0) }}</div>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12"><p class="text-muted mb-0">No hay ofertas activas por el momento.</p></div>
            @endforelse
        </div>
    </section>
</x-plantilla>