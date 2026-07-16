<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Dashboard | Admin' }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* Fondo gris muy claro para el área de contenido principal */
        body {
            background-color: #f8f9fa;
        }

        /* Comportamiento responsivo para pantallas medianas y superiores (Desktop) */
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 100;
                width: 240px; /* Ancho fijo para el sidebar */
                height: 100vh;
                background-color: #ffffff !important; /* Sidebar Blanco */
                border-right: 1px solid #e0e0e0 !important; /* Borde sutil gris */
            }
            .main-content { 
                margin-left: 240px; /* Desplaza el contenido a la derecha */
                width: calc(100% - 240px); /* Ocupa el ancho restante de la pantalla */
            }
        }

        /* Estado Activo personalizado con el tono Rosa (#d75078) */
        .nav-link.active-dentissa { 
            background-color: #d75078 !important; 
            color: #ffffff !important;
            font-weight: 500;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(215, 80, 120, 0.2);
        }

        /* Efecto Hover interactivo con un tono gris suave para fondo blanco */
        .link-hover:hover { 
            background-color: #f1f3f5 !important; 
            color: #d75078 !important; /* El texto brilla en rosa al pasar el mouse */
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        /* Estilo para los elementos hover dentro del menú desplegable */
        .link-hover-drop:hover {
            background-color: #d75078 !important;
            color: #ffffff !important;
        }
        
        .card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
        }
        .text-dentissa { color: #d75078 !important; }
        .bg-dentissa { background-color: #d75078 !important; }
        .bg-dentissa:hover { background-color: #c24066 !important; }
    </style>
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            
            <!-- COLUMNA DE LA BARRA LATERAL (Izquierda) -->
            <div class="col-md-3 col-lg-2 p-0 sidebar">
                <x-sidevar />
            </div>

            <!-- COLUMNA DEL CONTENIDO PRINCIPAL (Derecha) -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                
                <!-- Navbar para móviles (aparece únicamente en pantallas pequeñas) -->
                <div class="d-md-none navbar navbar-expand-lg navbar-light bg-white p-3 mb-3 border-bottom">
                    <span class="navbar-brand fw-bold text-dentissa">Dentissa</span>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Contenido dinámico inyectado desde la vista -->
                <div class="pt-4 pb-5">
                    {{ $slot }}
                </div>

            </main>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>