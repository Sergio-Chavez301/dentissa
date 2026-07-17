<x-plantilla>
    <x-slot:title>Panel de Administración | Melissa López N.</x-slot:title>

    <!-- Encabezado de Bienvenida -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Bienvenido de nuevo, {{ Auth::user()->nombre }}</h1>
            <p class="text-muted">Gestión clínica y administrativa de Odontología Integral</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-dentissa py-2 px-3 fs-6 rounded-pill shadow-sm text-white">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>



</x-plantilla>