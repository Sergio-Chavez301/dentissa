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

    @if(config('mobile.android_app_download_url'))
        <section class="card border-0 shadow-sm mb-4" aria-label="Aplicación móvil">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">Lleva Dentissa contigo</h2>
                    <p class="text-muted mb-0">Descarga la app para Android y consulta tu información desde tu teléfono.</p>
                </div>
                <a class="btn btn-outline-primary" href="{{ config('mobile.android_app_download_url') }}" rel="noopener">
                    Descargar app Android
                </a>
            </div>
        </section>
    @endif

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
                                <th>Atención</th>
                                <th>Tratamiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->citas->sortByDesc(fn ($cita) => $cita->fecha . ' ' . $cita->hora) as $cita)
                                @php
                                    $estadoCita = $estadosCita[$cita->estado] ?? [ucfirst(str_replace('_', ' ', $cita->estado)), 'text-bg-secondary'];
                                @endphp
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
                                    <td>{{ substr($cita->hora, 0, 5) }}</td>
                                    <td>{{ $cita->tratamiento_realizado ?: ($cita->servicio?->nombre ?? 'Consulta general') }}</td>
                                    <td>{{ $cita->casoClinico?->tratamiento_base ?? 'Sin tratamiento asociado' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="badge {{ $estadoCita[1] }}">{{ $estadoCita[0] }}</span>
                                            <a href="{{ route('citas.show', $cita) }}" class="btn btn-sm btn-outline-secondary">Ver cita</a>
                                            @if(in_array($cita->estado, [\App\Models\Cita::ESTADO_EN_ESPERA, \App\Models\Cita::ESTADO_CONFIRMADA], true))
                                                <form action="{{ route('citas.cancel', $cita) }}" method="POST" class="m-0" onsubmit="return confirm('¿Cancelar esta cita?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
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
            @php
                $progreso = min(100, max(0, (int) $caso->progreso));
            @endphp
            <article class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div>
                            <h3 class="h6 fw-bold mb-1">{{ $caso->tratamiento_base ?? 'Tratamiento dental' }}</h3>
                            <a href="{{ route('casos.show', $caso) }}" class="btn btn-sm btn-outline-secondary">Ver tratamiento</a>
                        </div>
                        <span class="badge {{ $caso->estado === 'activo' ? 'text-bg-success' : 'text-bg-secondary' }}">{{ ucfirst($caso->estado) }}</span>
                    </div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-muted">Progreso del tratamiento</span>
                        <span class="fw-semibold">{{ $progreso }}%</span>
                    </div>
                    <div class="progress mb-3" role="progressbar" aria-label="Progreso del tratamiento" aria-valuenow="{{ $progreso }}" aria-valuemin="0" aria-valuemax="100" style="height: 8px;">
                        <div class="progress-bar bg-dentissa" style="width: {{ $progreso }}%"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                        <h4 class="small fw-bold text-muted text-uppercase mb-0">Citas de este tratamiento</h4>
                        @if($caso->estado === \App\Models\CasoClinico::ESTADO_ACTIVO)
                            <a href="{{ route('patients.treatment.request', $caso) }}" class="btn btn-sm btn-dentissa text-nowrap">
                                <i class="bi bi-calendar-plus me-1"></i>Solicitar cita
                            </a>
                        @endif
                    </div>
                    @php
                        $citasRealizadas = $caso->citas
                            ->sortByDesc(fn ($cita) => $cita->fecha . ' ' . $cita->hora);
                    @endphp
                    @if($citasRealizadas->isNotEmpty())
                        <ul class="list-group list-group-flush">
                            @foreach($citasRealizadas as $citaRealizada)
                                <li class="list-group-item px-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <span>{{ $citaRealizada->tratamiento_realizado ?: ($citaRealizada->servicio?->nombre ?? 'Consulta general') }}</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted text-nowrap">{{ \Illuminate\Support\Carbon::parse($citaRealizada->fecha)->format('d/m/Y') }} · {{ substr($citaRealizada->hora, 0, 5) }}</span>
                                        <a href="{{ route('citas.show', $citaRealizada) }}" class="btn btn-sm btn-outline-secondary">Ver cita</a>
                                        @if(in_array($citaRealizada->estado, [\App\Models\Cita::ESTADO_EN_ESPERA, \App\Models\Cita::ESTADO_CONFIRMADA], true))
                                            <form action="{{ route('citas.cancel', $citaRealizada) }}" method="POST" class="m-0" onsubmit="return confirm('¿Cancelar esta cita?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Cancelar</button>
                                            </form>
                                        @endif
                                    </div>
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
            @if($officeSettings->whatsapp_digits)
                @php
                    $allOffersMessage = rawurlencode('Hola, quisiera información sobre las ofertas de ' . $officeSettings->clinic_name . '.');
                @endphp
                <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $allOffersMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success" onclick="return confirm('Se abrirá WhatsApp para solicitar información sobre las ofertas. ¿Deseas continuar?')">
                    <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Consultar ofertas
                </a>
            @else
                <button type="button" class="btn btn-sm btn-outline-success" onclick="alert('El WhatsApp del consultorio aún no está configurado. Contacta al administrador.')">
                    <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Consultar ofertas
                </button>
            @endif
        </div>
        <p class="small text-muted mb-3">
            @if($officeSettings->whatsapp_digits)
                Al solicitar información, se abrirá WhatsApp con el nombre de la oferta.
            @else
                Para consultar una oferta, contacta directamente al consultorio.
            @endif
        </p>
        <div class="row g-3">
            @forelse($ofertas as $oferta)
                @php
                    $offerMessage = rawurlencode(
                        'Hola, quisiera información sobre la oferta "' . $oferta->titulo . '" de ' . $officeSettings->clinic_name . '. ¿Podrían compartirme los detalles y su disponibilidad?'
                    );
                @endphp
                <div class="col-md-4">
                    <article class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <span class="small text-dentissa fw-bold">{{ strtoupper($oferta->tag ?? 'PROMOCIÓN') }}</span>
                            <h3 class="h6 fw-bold mt-2">{{ $oferta->titulo }}</h3>
                            <p class="small text-muted mb-3">{{ $oferta->descripcion }}</p>
                            @if($oferta->precio)
                                <div class="fw-bold mb-3">${{ number_format((float) $oferta->precio, 0) }}</div>
                            @endif
                            @if($officeSettings->whatsapp_digits)
                                <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $offerMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success mt-auto" onclick="return confirm('Se abrirá WhatsApp para solicitar más información sobre esta oferta. ¿Deseas continuar?')">
                                    <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Solicitar más información
                                </a>
                            @else
                                <button type="button" class="btn btn-sm btn-outline-success mt-auto" onclick="alert('El WhatsApp del consultorio aún no está configurado. Contacta al administrador.')">
                                    <i class="bi bi-whatsapp me-1" aria-hidden="true"></i>Solicitar más información
                                </button>
                            @endif
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12"><p class="text-muted mb-0">No hay ofertas activas por el momento.</p></div>
            @endforelse
        </div>

        @if($officeSettings->map_embed_link || $officeSettings->map_link)
            <section class="card border-0 shadow-sm mt-4" aria-label="Ubicación del consultorio">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="text-muted small text-uppercase fw-bold">Ubicación</span>
                        <h3 class="h6 fw-bold mt-1 mb-1">{{ $officeSettings->clinic_name }}</h3>
                        <p class="text-muted mb-0">{{ $officeSettings->clinic_address ?: 'Ubicación próximamente disponible.' }}</p>
                    </div>
                    @if($officeSettings->map_link)
                        <a href="{{ $officeSettings->map_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-geo-alt me-1"></i>Cómo llegar
                        </a>
                    @endif
                </div>
                @if($officeSettings->map_embed_link)
                    <iframe
                        src="{{ $officeSettings->map_embed_link }}"
                        title="Mapa de ubicación de {{ $officeSettings->clinic_name }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                        style="display:block;width:100%;height:280px;border:0;border-radius:0 0 6px 6px">
                    </iframe>
                @endif
            </section>
        @endif
    </section>
</x-plantilla>