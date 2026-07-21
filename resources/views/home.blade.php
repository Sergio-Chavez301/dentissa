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
                <div class="d-flex mt-3 mt-lg-0">
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 w-lg-auto">Iniciar sesión</a>
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

    <!-- Pie de página -->
    <footer class="py-4 text-center border-top">
        <div class="container">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Melissa López N. - Odontología Integral</p>
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