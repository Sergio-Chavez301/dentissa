<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Equipo del Proyecto | Dentissa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .team-img-wrapper {
            width: 130px;
            height: 130px;
            margin: 0 auto;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--color-rosa);
        }
        .team-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
                <div class="d-flex mt-3 mt-lg-0 gap-2">
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
            </div>
        </div>
    </header>

    <!-- Listado de los 5 Integrantes del Proyecto -->
    <main class="container pb-5">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
            
            <!-- Integrante 1 -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="team-img-wrapper mb-3">
                        <img src="{{ asset('img/dev1.jpg') }}" alt="Integrante 1" onerror="this.src='https://via.placeholder.com/130?text=Dev+1'">
                    </div>
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5"></h4>
                        <span class="badge bg-rosa mb-2">Líder de Proyecto / Backend Developer</span>
                        <p class="card-text text-muted small mt-2">Responsable de la arquitectura general del sistema, lógica en Laravel, controladores y bases de datos.</p>
                    </div>
                </div>
            </div>

            <!-- Integrante 2 -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="team-img-wrapper mb-3">
                        <img src="{{ asset('img/dev2.jpg') }}" alt="Integrante 2" onerror="this.src='https://via.placeholder.com/130?text=Dev+2'">
                    </div>
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Nombre Integrante 2</h4>
                        <span class="badge bg-rosa mb-2">Frontend Developer / UI-UX</span>
                        <p class="card-text text-muted small mt-2">Encargado del diseño de interfaces, maquetación con Bootstrap y experiencia de usuario en las vistas.</p>
                    </div>
                </div>
            </div>

            <!-- Integrante 3 -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="team-img-wrapper mb-3">
                        <img src="{{ asset('img/dev3.jpg') }}" alt="Integrante 3" onerror="this.src='https://via.placeholder.com/130?text=Dev+3'">
                    </div>
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Nombre Integrante 3</h4>
                        <span class="badge bg-rosa mb-2">Gestor de Base de Datos</span>
                        <p class="card-text text-muted small mt-2">Diseño y estructuración de tablas relacionales en MySQL, migraciones y consultas optimizadas.</p>
                    </div>
                </div>
            </div>

            <!-- Integrante 4 -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="team-img-wrapper mb-3">
                        <img src="{{ asset('img/dev4.jpg') }}" alt="Integrante 4" onerror="this.src='https://via.placeholder.com/130?text=Dev+4'">
                    </div>
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Nombre Integrante 4</h4>
                        <span class="badge bg-rosa mb-2">Control de Calidad / Testing</span>
                        <p class="card-text text-muted small mt-2">Pruebas funcionales de los módulos de citas, validaciones de formularios y control de errores del sistema.</p>
                    </div>
                </div>
            </div>

            <!-- Integrante 5 -->
            <div class="col">
                <div class="card team-card h-100 p-4 shadow-sm bg-white text-center">
                    <div class="team-img-wrapper mb-3">
                        <img src="{{ asset('img/dev5.jpg') }}" alt="Integrante 5" onerror="this.src='https://via.placeholder.com/130?text=Dev+5'">
                    </div>
                    <div class="card-body p-0">
                        <h4 class="card-title fw-bold text-rosa h5">Nombre Integrante 5</h4>
                        <span class="badge bg-rosa mb-2">Documentación / Despliegue</span>
                        <p class="card-text text-muted small mt-2">Soporte técnico, control de versiones en Git/GitHub y documentación de los requerimientos del proyecto.</p>
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