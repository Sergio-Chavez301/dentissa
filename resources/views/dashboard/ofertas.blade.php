<x-plantilla>
    <x-slot:title>Ofertas | Dentissa</x-slot:title>

    @php
        $dashboardRoute = match ((int) (Auth::user()?->role_id ?? 0)) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.asistente'),
            3 => route('dashboard.paciente'),
            default => route('home'),
        };
    @endphp

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom">
        <div>
            <a href="{{ $dashboardRoute }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Volver al dashboard
            </a>
            <span class="d-block text-uppercase text-dentissa mb-1 fw-bold small">{{ $officeSettings->clinic_name }}</span>
            <h1 class="h2 fw-bold mb-2">Ofertas y promociones</h1>
            <p class="text-secondary mb-0">Solicita información por WhatsApp sobre la promoción que te interesa.</p>
        </div>
        @if($officeSettings->whatsapp_digits)
            @php
                $generalMessage = rawurlencode('Hola, quisiera información sobre las ofertas de ' . $officeSettings->clinic_name . '.');
            @endphp
            <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $generalMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-success" onclick="return confirm('Se abrirá WhatsApp para solicitar información sobre las ofertas. ¿Deseas continuar?')">
                <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Consultar por WhatsApp
            </a>
        @else
            <button type="button" class="btn btn-success" onclick="alert('El WhatsApp del consultorio aún no está configurado. Contacta al administrador.')">
                <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Consultar por WhatsApp
            </button>
        @endif
    </div>

    @if($officeSettings->whatsapp_digits)
        <div class="alert alert-light border shadow-sm small mb-4">
            <i class="bi bi-whatsapp text-success me-2" aria-hidden="true"></i>
            Al solicitar información se abrirá WhatsApp y se incluirá el nombre de la oferta seleccionada.
        </div>
    @endif

    @if($ofertas->isNotEmpty())
        <div class="row g-3">
            @foreach($ofertas as $oferta)
                @php
                    $offerMessage = rawurlencode(
                        'Hola, quisiera información sobre la oferta "' . $oferta->titulo . '" de ' . $officeSettings->clinic_name . '. ¿Podrían compartirme los detalles y su disponibilidad?'
                    );
                @endphp
                <div class="col-md-6 col-xl-4">
                    <article class="card border-0 shadow-sm h-100">
                        <div class="card-body p-4 d-flex flex-column">
                            <span class="badge rounded-pill text-bg-light text-dentissa align-self-start mb-3">{{ $oferta->tag ?? 'Promoción' }}</span>
                            <h2 class="h5 fw-bold mb-2">{{ $oferta->titulo }}</h2>
                            @if($oferta->precio)
                                <div class="h3 fw-bold text-dentissa mb-3">${{ number_format((float) $oferta->precio, 0) }}</div>
                            @else
                                <div class="h3 fw-bold text-dentissa mb-3">Consulta</div>
                            @endif
                            <p class="text-muted mb-4">{{ $oferta->descripcion }}</p>
                            @if($officeSettings->whatsapp_digits)
                                <a href="https://wa.me/{{ $officeSettings->whatsapp_digits }}?text={{ $offerMessage }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success mt-auto" onclick="return confirm('Se abrirá WhatsApp para solicitar más información sobre esta oferta. ¿Deseas continuar?')">
                                    <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Solicitar más información
                                </a>
                            @else
                                <button type="button" class="btn btn-outline-success mt-auto" onclick="alert('El WhatsApp del consultorio aún no está configurado. Contacta al administrador.')">
                                    <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Solicitar más información
                                </button>
                            @endif
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-tag text-dentissa fs-2" aria-hidden="true"></i>
                <h2 class="h5 fw-bold mt-3">No hay ofertas activas</h2>
                <p class="text-muted mb-0">Pronto publicaremos nuevas promociones.</p>
            </div>
        </div>
    @endif
</x-plantilla>
