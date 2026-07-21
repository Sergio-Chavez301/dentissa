<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dentissa | Dashboard' }}</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 100;
                width: 240px;
            }
            .main-content {
                margin-left: 240px;
            }
        }
        /* Paleta de colores institucional */
        .bg-dentissa {
            background-color: #d75078 !important;
        }
        .text-dentissa {
            color: #d75078 !important;
        }
        .active-dentissa {
            background-color: #d75078 !important;
            color: #ffffff !important;
        }
        .link-hover:hover {
            background-color: rgba(242, 176, 166, 0.15);
        }
    </style>
</head>
<body>

    <!-- NAVBAR SUPERIOR MÓVIL (Muestra el botón de hamburguesa en pantallas pequeñas) -->
    <nav class="navbar navbar-light bg-white border-bottom d-md-none px-3 sticky-top" style="z-index: 1020;">
        <div class="container-fluid p-0">
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="navbar-brand fs-6 fw-bold text-dark mb-0">Melissa López N.</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (Barra Lateral) -->
            <x-sidebar /> 

            <!-- Contenedor Principal de la Vista -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>