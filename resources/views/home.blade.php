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
        
        /* Ajuste responsivo para el encabezado de fondo */
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

    <!-- Sección Informativa -->
    <section class="container py-5">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-12 col-md-6 text-center text-md-start">
                <h2 class="text-rosa fw-bold display-6 display-md-5">Comprometidos con tu salud</h2>
                <p class="fs-5 text-muted">Ofrecemos servicios de alta calidad con un enfoque humano, garantizando un entorno profesional y confiable para toda la familia.</p>
            </div>
            <div class="col-12 col-md-6">
                <div class="card p-4 p-md-5 border-0 shadow" style="background-color: #fffafb;">
                    <h4 class="text-rosa">¿Ya eres paciente?</h4>
                    <p class="text-muted">Accede a tu historial clínico y gestiona tus citas desde nuestro panel exclusivo.</p>
                    <a href="{{ route('login') }}" class="btn btn-rosa btn-lg mt-3 w-100">Acceder al Panel</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Pie de página -->
    <footer class="py-4 text-center border-top">
        <div class="container">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Melissa López N. - Odontología Integral</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>