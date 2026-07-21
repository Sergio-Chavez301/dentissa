<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Melissa López N. | Odontología Integral</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --color-rosa: #d75078;
            --color-suave: #f2b0a6;
        }
        body { font-family: sans-serif; }
        .text-rosa { color: var(--color-rosa) !important; }
        .bg-rosa { background-color: var(--color-rosa) !important; color: white; }
        .btn-rosa { 
            background-color: var(--color-rosa) !important; 
            border-color: var(--color-rosa) !important; 
            color: white; 
        }
        .btn-rosa:hover { background-color: #c0466b !important; color: white; }
        .navbar { border-bottom: 3px solid var(--color-rosa) !important; }
        
        .hero-header {
            background-image: url('{{ asset('img/fondo2.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 40vh;
        }
        @media (min-width: 768px) {
            .hero-header {
                min-height: 60vh;
            }
        }
        .service-card, .case-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
        }
        .service-card:hover, .case-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(215, 80, 120, 0.15);
        }
        .discount-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 50rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        footer a {
            text-decoration: none;
            transition: color 0.2s;
        }
        footer a:hover {
            color: var(--color-rosa) !important;
        }
    </style>
</head>
<body>  
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-rosa" href="#">Melissa López N.</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Encabezado / Hero Section -->
    <header class="hero-header text-center position-relative d-flex align-items-center justify-content-center">
    </header>

    <!-- Sección Informativa con el Botón para el Formulario -->
    <section class="container py-5">
        
        <!-- Alerta de éxito cuando envían la solicitud -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-5" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-12 col-lg-6 text-center text-lg-start">
                <h2 class="text-rosa fw-bold display-6">Comprometidos con tu salud</h2>
                <p class="fs-5 text-muted mb-4">Ofrecemos servicios de alta calidad con un enfoque humano, garantizando un entorno profesional y confiable para toda la familia.</p>
                
                <!-- Botón que activa la ventana emergente del formulario -->
                <button type="button" class="btn btn-rosa btn-lg px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalSolicitud">
                    Agendar Cita Ahora
                </button>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card p-4 p-md-5 border-0 shadow" style="background-color: #fffafb;">
                    <h4 class="text-rosa">¿Ya eres paciente?</h4>
                    <p class="text-muted">Accede a tu historial clínico y gestiona tus citas desde nuestro panel exclusivo.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg mt-3 w-100">Acceder al Panel</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE SERVICIOS E IMÁGENES -->
    <section class="py-5 bg-light border-top border-bottom">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-uppercase text-rosa fw-bold small tracking-wider">Tratamientos Integrales</span>
                <h2 class="fw-bold text-dark">Nuestros Servicios Destacados</h2>
                <p class="text-muted">Cuidamos cada detalle de tu sonrisa con tecnología de vanguardia.</p>
            </div>
            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card service-card h-100 shadow-sm bg-white overflow-hidden">
                        <img src="{{ asset('img/servicio-ortodoncia.jpg') }}" class="card-img-top" alt="Ortodoncia Avanzada" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h4 class="card-title text-rosa fw-bold h5">Ortodoncia Avanzada</h4>
                            <p class="card-text text-muted small mt-2">Alinea tus dientes y mejora tu mordida con brackets estéticos y opciones modernas adaptadas a ti.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card service-card h-100 shadow-sm bg-white overflow-hidden">
                        <img src="{{ asset('img/servicio-sonrisa.jpg') }}" class="card-img-top" alt="Diseño de Sonrisa" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h4 class="card-title text-rosa fw-bold h5">Diseño de Sonrisa</h4>
                            <p class="card-text text-muted small mt-2">Blanqueamientos profesionales, carillas y restauraciones estéticas para devolverle el brillo natural a tus dientes.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card service-card h-100 shadow-sm bg-white overflow-hidden">
                        <img src="{{ asset('img/servicio-preventiva.jpg') }}" class="card-img-top" alt="Odontología Preventiva" style="height: 200px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h4 class="card-title text-rosa fw-bold h5">Odontología Preventiva</h4>
                            <p class="card-text text-muted small mt-2">Limpiezas profundas, revisiones periódicas y selladores para prevenir cualquier molestia futura.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE DESCUENTOS Y PROMOCIONES -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <span class="text-uppercase text-rosa fw-bold small">Ahorra en tu salud bucal</span>
            <h2 class="fw-bold text-dark">Promociones Especiales</h2>
            <p class="text-muted">Aprovecha nuestros descuentos vigentes por tiempo limitado.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card case-card p-4 position-relative border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #d75078, #f2b0a6);">
                    <span class="discount-badge">20% OFF</span>
                    <h3 class="fw-bold h4">Blanqueamiento Láser</h3>
                    <p class="small mb-3">Luze una sonrisa más blanca y deslumbrante en una sola sesión con la más alta tecnología.</p>
                    <button type="button" class="btn btn-light text-rosa fw-bold" data-bs-toggle="modal" data-bs-target="#modalSolicitud">Aprovechar Promoción</button>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card case-card p-4 position-relative border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #4a5568, #2d3748);">
                    <span class="discount-badge">GRATIS</span>
                    <h3 class="fw-bold h4">Valoración y Diagnóstico</h3>
                    <p class="small mb-3">Tu primera consulta de evaluación general y presupuesto inicial no tienen ningún costo.</p>
                    <button type="button" class="btn btn-light text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#modalSolicitud">Agendar Gratis</button>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE CASOS DE ÉXITO (ANTES / DESPUÉS) -->
    <section class="py-5 bg-light border-top">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-uppercase text-rosa fw-bold small">Resultados Reales</span>
                <h2 class="fw-bold text-dark">Casos de Éxito</h2>
                <p class="text-muted">Transformaciones que devuelven la confianza al sonreír.</p>
            </div>

            <div class="row row-cols-1 row-cols-md-2 g-4">
                <div class="col">
                    <div class="card case-card h-100 shadow-sm bg-white overflow-hidden">
                        <img src="{{ asset('img/caso1.jpg') }}" class="card-img-top" alt="Caso de éxito ortodoncia" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h4 class="text-rosa fw-bold h5">Corrección de Mordida y Alineación</h4>
                            <p class="text-muted small">Tratamiento ortodóncico integral de 14 meses con resultados funcionales y estéticos sobresalientes.</p>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card case-card h-100 shadow-sm bg-white overflow-hidden">
                        <img src="{{ asset('img/caso2.jpg') }}" class="card-img-top" alt="Caso de éxito diseño de sonrisa" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h4 class="text-rosa fw-bold h5">Rehabilitación y Carillas Estéticas</h4>
                            <p class="text-muted small">Devolución de la armonía y brillo natural a la sonrisa mediante carillas de porcelana de alta durabilidad.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE COMENTARIOS / TESTIMONIOS -->
    <section class="container py-5">
        <div class="text-center mb-5">
            <span class="text-uppercase text-rosa fw-bold small">Testimonios</span>
            <h2 class="fw-bold text-dark">Lo que dicen nuestros pacientes</h2>
            <p class="text-muted">La satisfacción de quienes confían en nosotros es nuestra mejor carta de presentación.</p>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card h-100 border-0 shadow-sm p-4 bg-white">
                    <div class="mb-3 text-warning">★★★★★</div>
                    <p class="text-muted fst-italic small">"Excelente atención por parte de la Dra. Melissa. Muy profesional, cuidadosa y el consultorio impecable. Cero dolor."</p>
                    <h6 class="fw-bold text-dark mt-auto mb-0">— Mariana G.</h6>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0 shadow-sm p-4 bg-white">
                    <div class="mb-3 text-warning">★★★★★</div>
                    <p class="text-muted fst-italic small">"Me hice un diseño de sonrisa y el cambio fue radical. Explicaron todo el proceso detalladamente y el resultado superó mis expectativas."</p>
                    <h6 class="fw-bold text-dark mt-auto mb-0">— Carlos R.</h6>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0 shadow-sm p-4 bg-white">
                    <div class="mb-3 text-warning">★★★★★</div>
                    <p class="text-muted fst-italic small">"Llevé a mis hijos a revisión y su trato con los niños es maravilloso. 100% recomendados para toda la familia."</p>
                    <h6 class="fw-bold text-dark mt-auto mb-0">— Sofía M.</h6>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal con el Formulario de Solicitud -->
    <div class="modal fade" id="modalSolicitud" tabindex="-1" aria-labelledby="modalSolicitudLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title text-rosa fw-bold" id="modalSolicitudLabel">Solicita tu Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">Completa tus datos y nos pondremos en contacto o confirmaremos tu cita pronto.</p>
                    
                    <form action="{{ route('solicitud.storePublic') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre" class="form-label">Nombre(s)</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Correo (Opcional)</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                        </div>

                        <!-- Selector de Fecha Propuesta -->
                        <div class="mb-3">
                            <label for="fecha_propuesta" class="form-label">Fecha de la Cita</label>
                            <input type="date" class="form-control" id="fecha_propuesta" name="fecha_propuesta" onchange="cargarHorariosPublicos()" required>
                        </div>

                        <!-- Selector Dinámico de Horas Disponibles -->
                        <div class="mb-3">
                            <label for="hora_propuesta" class="form-label">Hora Disponible</label>
                            <select id="hora_propuesta" name="hora_propuesta" class="form-select" required>
                                <option value="">Seleccione una fecha primero...</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="motivo_consulta" class="form-label">Motivo de la Consulta</label>
                            <textarea class="form-control" id="motivo_consulta" name="motivo_consulta" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-rosa shadow-sm">Enviar Solicitud</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie de página con Contactos e Información -->
    <footer class="bg-dark text-white pt-5 pb-4 border-top">
        <div class="container text-center text-md-start">
            <div class="row g-4">
                <!-- Columna 1: Marca -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-rosa fw-bold mb-3">Melissa López N.</h5>
                    <p class="text-white-55 small">Odontología Integral de alta calidad orientada a brindarte la mejor sonrisa y salud bucal con un trato humano y profesional.</p>
                </div>

                <!-- Columna 2: Contacto -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-rosa fw-bold mb-3">Contacto</h5>
                    <p class="text-white-55 small mb-1">📞 Teléfono: +52 (55) 1234-5678</p>
                    <p class="text-white-55 small mb-1">✉️ Correo: contacto@melissalopez.com</p>
                    <p class="text-white-55 small mb-0">🕒 Lunes a Sábado: 9:00 am - 7:00 pm</p>
                </div>

                <!-- Columna 3: Enlaces de Interés / Equipo -->
                <div class="col-md-4 mb-3">
                    <h5 class="text-rosa fw-bold mb-3">Enlaces de Interés</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('acerca-de') }}" class="text-white-55 small">👥 Conoce al Equipo de Desarrollo</a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('login') }}" class="text-white-55 small">🔐 Iniciar Sesión en el Panel</a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-55 small mb-0">&copy; {{ date('Y') }} Melissa López N. - Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script para consultar la disponibilidad desde la página pública -->
    <script>
        function cargarHorariosPublicos() {
            let fecha = document.getElementById('fecha_propuesta').value;
            let selectHora = document.getElementById('hora_propuesta');
            
            if (!fecha) return;
            selectHora.innerHTML = '<option>Cargando horarios...</option>';

            fetch(`/api/disponibilidad?fecha=${fecha}`)
                .then(r => r.json())
                .then(data => {
                    selectHora.innerHTML = '<option value="">Seleccione una hora</option>';
                    if(data.horarios && data.horarios.length > 0) {
                        data.horarios.forEach(h => {
                            selectHora.innerHTML += `<option value="${h}">${h.substring(0, 5)}</option>`;
                        });
                    } else {
                        selectHora.innerHTML = '<option value="">No hay horarios disponibles para este día</option>';
                    }
                })
                .catch(error => {
                    selectHora.innerHTML = '<option value="">Error al cargar horarios</option>';
                });
        }
    </script>
</body>
</html>