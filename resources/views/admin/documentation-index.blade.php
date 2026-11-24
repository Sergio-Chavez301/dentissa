<x-plantilla>
    <x-slot:title>Documentación del sistema | Dentissa</x-slot:title>

    <style>
        .documentation-hub {
            max-width: 1180px;
            margin: 0 auto;
        }

        .documentation-card {
            height: 100%;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .documentation-hub .btn {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease;
        }

        .documentation-hub .btn:focus-visible,
        .documentation-hub .documentation-card:focus-visible {
            outline: 3px solid rgba(215, 80, 120, 0.45);
            outline-offset: 3px;
        }

        .documentation-hub .documentation-card .card-footer {
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .documentation-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1.25rem rgba(30, 30, 36, 0.1) !important;
        }

        .documentation-diagram {
            overflow-x: auto;
            border: 1px solid #e8e4e6;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #fff 0%, #fff8fa 100%);
        }

        .documentation-diagram svg {
            display: block;
            width: 100%;
            min-width: 720px;
            height: auto;
        }

        .documentation-diagram text {
            font-family: "Segoe UI", Arial, sans-serif;
        }

        .documentation-process {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            gap: 0.65rem;
        }

        .documentation-process-step {
            flex: 1 1 145px;
            padding: 0.85rem;
            border: 1px solid #eadfe3;
            border-top: 3px solid #d75078;
            border-radius: 0.5rem;
            background: #fff;
        }

        .documentation-process-step strong {
            display: block;
            margin-bottom: 0.25rem;
        }

        .documentation-process-arrow {
            align-self: center;
            color: #d75078;
            font-size: 1.25rem;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .documentation-process-arrow {
                display: none;
            }
        }
    </style>

    <div class="documentation-hub">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
            <div>
                <span class="text-uppercase fw-semibold small text-dentissa">Centro de ayuda del administrador</span>
                <h1 class="h3 fw-bold mt-1 mb-1">Documentación de Dentissa</h1>
                <p class="text-muted mb-0">Guías técnicas, procesos operativos y diagramas del sistema en un solo lugar.</p>
            </div>
            <a href="{{ route('dashboard.admin') }}" class="btn btn-dentissa shrink-0">
                <i class="bi bi-arrow-left me-1"></i>Volver al panel
            </a>
        </div>

        <section class="card border-0 shadow-sm mb-4" aria-labelledby="architecture-title">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
                    <div>
                        <h2 id="architecture-title" class="h5 fw-bold mb-1">Mapa de arquitectura</h2>
                        <p class="small text-muted mb-0">La app abre el portal web; Laravel aplica los permisos y consulta los datos.</p>
                    </div>
                    <span class="badge rounded-pill text-bg-light align-self-start">Vista general</span>
                </div>
                <div class="documentation-diagram" role="img" aria-label="La web pública y la app Android llegan al portal Laravel; la app usa el portal por HTTPS y sesión web. Laravel aplica permisos y consulta la base de datos. La API y las notificaciones push no son usadas por la app actual.">
                    <svg viewBox="0 0 1000 370" xmlns="http://www.w3.org/2000/svg" aria-labelledby="architecture-svg-title architecture-svg-desc">
                        <title id="architecture-svg-title">Arquitectura de Dentissa</title>
                        <desc id="architecture-svg-desc">La web pública y la app Android acceden al portal Laravel. La app muestra el portal web con una sesión. Laravel valida roles y consulta la base de datos configurada. La API Sanctum existe aparte, pero la app actual no la consume ni registra notificaciones push.</desc>
                        <defs>
                            <marker id="architecture-arrow" markerWidth="10" markerHeight="10" refX="8" refY="3" orient="auto" markerUnits="strokeWidth">
                                <path d="M0,0 L0,6 L9,3 z" fill="#9c7080"/>
                            </marker>
                            <linearGradient id="architecture-main" x1="0" x2="1">
                                <stop offset="0" stop-color="#fff0f4"/>
                                <stop offset="1" stop-color="#ffe4eb"/>
                            </linearGradient>
                        </defs>
                        <g fill="none" stroke="#9c7080" stroke-width="2" marker-end="url(#architecture-arrow)">
                            <path d="M220 85 C290 85 290 130 360 130"/>
                            <path d="M220 245 C290 245 290 185 360 185"/>
                            <path d="M590 145 L690 92"/>
                        </g>
                        <g font-size="13" fill="#5d5257" text-anchor="middle">
                            <rect x="25" y="45" width="195" height="80" rx="12" fill="#fff" stroke="#e6c6d0"/>
                            <text x="122" y="76" font-size="15" font-weight="700">Portal web</text>
                            <text x="122" y="99">Pacientes y personal</text>
                            <rect x="25" y="205" width="195" height="80" rx="12" fill="#fff" stroke="#e6c6d0"/>
                            <text x="122" y="236" font-size="15" font-weight="700">App Android</text>
                            <text x="122" y="259">WebView · sesión HTTPS</text>
                            <rect x="360" y="95" width="230" height="130" rx="16" fill="url(#architecture-main)" stroke="#d75078" stroke-width="2"/>
                            <text x="475" y="137" font-size="19" font-weight="700" fill="#a33154">Laravel</text>
                            <text x="475" y="162">Portal web · sesión · roles</text>
                            <text x="475" y="188">Agenda · reglas · permisos</text>
                            <rect x="690" y="45" width="270" height="95" rx="12" fill="#fff" stroke="#b9d8ca"/>
                            <text x="825" y="80" font-size="16" font-weight="700">Base de datos</text>
                            <text x="825" y="104">Expedientes, citas y servicios</text>
                            <text x="825" y="124">Motor según configuración</text>
                            <rect x="360" y="270" width="600" height="48" rx="10" fill="#f3f6f4" stroke="#cbd8d2"/>
                            <text x="660" y="290" font-size="13" font-weight="700">API Sanctum separada · no la consume la app actual</text>
                            <text x="660" y="307" font-size="12">Push no está activo: el dispositivo no registra su token</text>
                        </g>
                    </svg>
                </div>
                <p class="small text-muted mt-2 mb-0">La app Android abre <code>/login?mobile=1</code> y utiliza una sesión Laravel para presentar el panel correspondiente al rol. Las pantallas y permisos se sirven desde el portal; los cambios web se publican en el servidor. La API Sanctum y parte de la infraestructura push permanecen en el repositorio, pero no son el flujo de la app actual.</p>
            </div>
        </section>

        <section class="card border-0 shadow-sm mb-4" aria-labelledby="mobile-flow-title">
            <div class="card-body p-3 p-md-4">
                <h2 id="mobile-flow-title" class="h5 fw-bold mb-1">Flujo de acceso desde la app</h2>
                <p class="small text-muted mb-3">El contenedor Android conduce al portal; el servidor elige y protege el panel.</p>
                <div class="documentation-process" aria-label="Inicio de sesión y acceso según el rol desde la aplicación">
                    <div class="documentation-process-step"><span class="small text-dentissa fw-semibold">01 · ANDROID</span><strong>Abre el portal seguro</strong><span class="small text-muted">Usa la URL HTTPS configurada.</span></div>
                    <span class="documentation-process-arrow" aria-hidden="true">→</span>
                    <div class="documentation-process-step"><span class="small text-dentissa fw-semibold">02 · LOGIN</span><strong>Laravel inicia sesión</strong><span class="small text-muted">Valida cuenta y contraseña.</span></div>
                    <span class="documentation-process-arrow" aria-hidden="true">→</span>
                    <div class="documentation-process-step"><span class="small text-dentissa fw-semibold">03 · ROL</span><strong>Abre el panel autorizado</strong><span class="small text-muted">Admin, asistente o paciente.</span></div>
                    <span class="documentation-process-arrow" aria-hidden="true">→</span>
                    <div class="documentation-process-step"><span class="small text-dentissa fw-semibold">04 · SERVIDOR</span><strong>Verifica cada ruta</strong><span class="small text-muted">La sesión no elimina controles de rol.</span></div>
                </div>
                <p class="small text-muted mt-3 mb-0"><strong>Push:</strong> la app actual no registra el dispositivo. No se deben esperar notificaciones push aunque el repositorio conserve endpoints y componentes del backend.</p>
            </div>
        </section>

        <section class="mb-4" aria-labelledby="docs-title">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-2 mb-3">
                <div>
                    <h2 id="docs-title" class="h5 fw-bold mb-1">Documentación del proyecto</h2>
                    <p class="small text-muted mb-0">Las páginas de esta sección están disponibles únicamente para administradores autenticados.</p>
                </div>
                <a href="{{ route('admin.mobile-app-guide') }}" class="btn btn-dentissa btn-sm">
                    <i class="bi bi-phone me-1"></i>Guía de la app móvil
                </a>
            </div>
            <div class="row g-3">
                @foreach($documents as $filename => $document)
                    @if($filename !== 'index.html')
                        <div class="col-12 col-md-6 col-xl-4">
                            <a href="{{ route('admin.documentation.show', ['document' => $filename]) }}" class="card documentation-card text-decoration-none text-dark border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <span class="d-inline-flex rounded-circle bg-light text-dentissa p-2 mb-3">
                                        <i class="bi {{ match($filename) {
                                            'diagrama-base-datos.html' => 'bi-diagram-3',
                                            'flujo-sistema.html' => 'bi-signpost-split',
                                            default => 'bi-journal-text',
                                        } }} fs-5"></i>
                                    </span>
                                    <h3 class="h6 fw-bold">{{ $document['title'] }}</h3>
                                    <p class="small text-muted mb-0">{{ $document['description'] }}</p>
                                </div>
                                <div class="card-footer bg-white border-0 px-4 pb-3 pt-0 small text-dentissa fw-semibold">
                                    Abrir documento <i class="bi bi-arrow-up-right ms-1"></i>
                                </div>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
        </section>

        <div class="alert alert-warning border-0 small mb-0" role="note">
            <i class="bi bi-info-circle me-1"></i>
            Los diagramas describen el código y las migraciones del repositorio. Antes de tomar decisiones sobre producción, confirma que el esquema y la configuración del hosting estén actualizados.
        </div>
    </div>
</x-plantilla>
