<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dentissa | Dashboard' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        html, body {
            width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .app-shell,
        .app-shell > .row {
            min-height: 100vh;
        }

        .row {
            --bs-gutter-x: 0;
        }

        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                height: 100vh;
                height: 100dvh;
                left: 0;
                z-index: 100;
                width: 240px;
                overflow-y: auto;
                overscroll-behavior: contain;
                scrollbar-gutter: stable;
            }

            .main-content {
                margin-left: 240px;
                width: calc(100% - 240px);
                max-width: none;
                min-width: 0;
                height: 100vh;
                height: 100dvh;
                overflow-y: auto;
                overscroll-behavior-y: contain;
                scrollbar-gutter: stable;
            }
        }

        @media (max-width: 767.98px) {
            .app-shell,
            .app-shell > .row {
                min-height: 100vh;
            }

            .sidebar {
                position: fixed;
                top: 56px;
                bottom: 0;
                left: 0;
                z-index: 1030;
                width: min(300px, 85vw);
                height: calc(100vh - 56px);
                height: calc(100dvh - 56px);
                overflow-y: auto;
                overscroll-behavior: contain;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                max-width: 100%;
                height: auto;
                min-height: calc(100vh - 56px);
                overflow: visible;
            }
        }

        /* Paleta de colores institucional */
        .bg-dentissa {
            background-color: #d75078 !important;
        }
        .text-dentissa {
            color: #d75078 !important;
        }
        .btn-dentissa {
            color: #ffffff !important;
            background-color: #d75078 !important;
            border-color: #d75078 !important;
        }
        .btn-dentissa:hover,
        .btn-dentissa:focus-visible {
            color: #ffffff !important;
            background-color: #c24066 !important;
            border-color: #c24066 !important;
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

    <nav class="navbar navbar-light bg-white border-bottom d-md-none px-3 sticky-top" style="z-index: 1020;">
        <div class="container-fluid p-0">
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="navbar-brand fs-6 fw-bold text-dark mb-0">Melissa López N.</span>
        </div>
    </nav>

    <div class="container-fluid app-shell">
        <div class="row">
            <x-sidebar /> 

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4 main-content">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>