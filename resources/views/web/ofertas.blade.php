<x-plantilla>
    <x-slot:title>Ofertas | Dentissa</x-slot:title>

    @php
        $dashboardRoute = Auth::check() ? match ((int) Auth::user()->role_id) {
            1 => route('dashboard.admin'),
            2 => route('dashboard.asistente'),
            3 => route('dashboard.paciente'),
            default => route('home'),
        } : route('home');
        $whatsappMessage = urlencode('Hola, quisiera información sobre las ofertas de ' . $officeSettings->clinic_name . '.');
        $whatsappUrl = $officeSettings->whatsapp_digits
            ? 'https://wa.me/' . $officeSettings->whatsapp_digits . '?text=' . $whatsappMessage
            : null;
    @endphp

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom">
        <div>
            <a href="{{ $dashboardRoute }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>{{ Auth::check() && (int) Auth::user()->role_id === 3 ? 'Volver a mi panel' : 'Volver al inicio' }}
            </a>
            <span class="d-block text-uppercase text-dentissa mb-1 fw-bold small">{{ $officeSettings->clinic_name }}</span>
            <h1 class="h2 fw-bold mb-0">Ofertas y promociones</h1>
        </div>
        @if($whatsappUrl)
            <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-success">
                <i class="bi bi-whatsapp me-2" aria-hidden="true"></i>Consultar por WhatsApp
            </a>
        @endif
    </div>

    @if($ofertas->isNotEmpty())
        <div class="row g-3">
            @foreach($ofertas as $oferta)
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
                            @if($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-success mt-auto">
                                    Consultar esta oferta
                                </a>
                            @else
                                <a href="{{ route('home') }}#contacto" class="btn btn-outline-secondary mt-auto">Solicitar información</a>
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

    <section class="card border-0 shadow-sm mt-4" aria-labelledby="office-location-title">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="text-muted small text-uppercase fw-bold">Visítanos</span>
                <h2 id="office-location-title" class="h5 fw-bold mt-1 mb-1">{{ $officeSettings->clinic_name }}</h2>
                <p class="text-muted mb-0">{{ $officeSettings->clinic_address ?: 'Ubicación próximamente disponible.' }}</p>
            </div>
            @if($officeSettings->map_link)
                <a href="{{ $officeSettings->map_link }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary">
                    <i class="bi bi-geo-alt me-2" aria-hidden="true"></i>Cómo llegar
                </a>
            @endif
        </div>
        @if($officeSettings->map_embed_link)
            <iframe
                src="{{ $officeSettings->map_embed_link }}"
                title="Mapa de ubicación de {{ $officeSettings->clinic_name }}"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
                style="display: block; width: 100%; height: 360px; border: 0; border-radius: 0 0 6px 6px;">
            </iframe>
        @endif
    </section>
</x-plantilla>