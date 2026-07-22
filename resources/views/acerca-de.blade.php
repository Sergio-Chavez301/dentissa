<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipo del Proyecto | Dentissa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --color-rosa: #d75078;
            --color-suave: #f2b0a6;
        }
        body { font-family: sans-serif; background-color: #fffafb; }
        .text-rosa { color: var(--color-rosa) !important; }
        .bg-rosa { background-color: var(--color-rosa) !important; color: white; }
        .btn-rosa { 
            background-color: var(--color-rosa) !important; 
            border-color: var(--color-rosa) !important; 
            color: white; 
        }
        .btn-rosa:hover { background-color: #c0466b !important; color: white; }
        .navbar { border-bottom: 3px solid var(--color-rosa) !important; }
        
        .team-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(215, 80, 120, 0.15);
        }
    </style>
</head>
<body>  
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-rosa" href="{{ route('home') }}">Dentissa | Proyecto</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <div class="d-flex mt-3 mt-lg-0 gap-2 align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">Inicio</a>
                    <a href="{{ route('login') }}" class="btn btn-rosa">Iniciar sesión</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Encabezado de la Sección -->
    <header class="py-5 text-center container">
        <div class="row py-lg-4">
            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-bold text-rosa display-5">Equipo de Desarrollo</h1>
                <p class="text-muted mt-2">Conoce a los integrantes detrás del sistema de gestión odontológica Dentissa.</p>
                
                <!-- Botón de Descarga de Documentación -->
                <div class="mt-4">
                    <a href="#" download class="btn btn-outline-secondary px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i> Descargar Documentación del Proyecto
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Listado de los Integrantes del Proyecto -->
    <main class="container pb-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
            
            <!-- Zuri Sarahi -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Zuri Sarahi Sanchez Rojas</h4>
                        <span class="badge bg-rosa mb-2">Líder y Organización del Proyecto</span>
                        <p class="card-text text-muted small mt-2">Coordinación general del equipo, planificación de tareas y cumplimiento de los objetivos del sistema.</p>
                    </div>
                </div>
            </div>

            <!-- Alexander -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Alexander Blanco Maciel</h4>
                        <span class="badge bg-rosa mb-2">Integrante 2</span>
                        <p class="card-text text-muted small mt-2">Apoyo en el desarrollo de funcionalidades, diseño de interfaces y optimización de vistas en la plataforma.</p>
                    </div>
                </div>
            </div>

            <!-- Sergio Gabriel -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Sergio Gabriel Chavez Del Carmen</h4>
                        <span class="badge bg-rosa mb-2">Desarrollo Backend / Base de Datos</span>
                        <p class="card-text text-muted small mt-2">Construcción de la lógica de negocio en Laravel, controladores, modelos y gestión de tablas relacionales.</p>
                    </div>
                </div>
            </div>

            <!-- Sarai Girón -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Sarai Girón Torres</h4>
                        <span class="badge bg-rosa mb-2">Integrante 4</span>
                        <p class="card-text text-muted small mt-2">Validaciones de formularios, pruebas de módulos y control de calidad para asegurar el funcionamiento correcto.</p>
                    </div>
                </div>
            </div>

            <!-- Yovanny Nuñez -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Yovanny Nuñez Martínez</h4>
                        <span class="badge bg-rosa mb-2">Documentación y Despliegue</span>
                        <p class="card-text text-muted small mt-2">Gestión del despliegue en entornos de producción, control de versiones y documentación técnica del proyecto.</p>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Pie de página -->
    <footer class="py-4 text-center border-top bg-white">
        <div class="container">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Dentissa - Sistema de Gestión Odontológica</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>