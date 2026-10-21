<x-plantilla>
    <x-slot:title>Datos del consultorio | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 pb-3 mb-4 border-bottom">
        <div>
            <a href="{{ route('dashboard.admin') }}" class="text-muted text-decoration-none small d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Volver al panel
            </a>
            <h1 class="h2 fw-bold text-dark mb-1">Datos del consultorio</h1>
            <p class="text-muted mb-0">Esta información aparecerá en la página pública y en las confirmaciones por WhatsApp.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="status">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="max-width: 820px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.office-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="clinic_name" class="form-label fw-semibold">Nombre del consultorio</label>
                    <input id="clinic_name" name="clinic_name" value="{{ old('clinic_name', $settings->clinic_name) }}" required maxlength="150" class="form-control @error('clinic_name') is-invalid @enderror">
                    @error('clinic_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="clinic_address" class="form-label fw-semibold">Dirección</label>
                    <textarea id="clinic_address" name="clinic_address" rows="3" required maxlength="500" class="form-control @error('clinic_address') is-invalid @enderror" placeholder="Calle, número, colonia, ciudad">{{ old('clinic_address', $settings->clinic_address) }}</textarea>
                    @error('clinic_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="map_url" class="form-label fw-semibold">Enlace de Google Maps (opcional)</label>
                    <input id="map_url" name="map_url" type="url" value="{{ old('map_url', $settings->map_url) }}" maxlength="1000" class="form-control @error('map_url') is-invalid @enderror" placeholder="https://maps.app.goo.gl/...">
                    <div class="form-text">Si lo dejas vacío, se crea un enlace de búsqueda usando la dirección.</div>
                    @error('map_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="whatsapp_phone" class="form-label fw-semibold">WhatsApp del consultorio</label>
                    <input id="whatsapp_phone" name="whatsapp_phone" type="tel" value="{{ old('whatsapp_phone', $settings->whatsapp_phone) }}" required maxlength="30" autocomplete="tel" class="form-control @error('whatsapp_phone') is-invalid @enderror" placeholder="+52 55 1234 5678">
                    <div class="form-text">Incluye la clave de país. Este número recibirá mensajes desde la web pública.</div>
                    @error('whatsapp_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-end gap-2 border-top pt-3">
                    <a href="{{ route('dashboard.admin') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-dentissa text-white"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Guardar datos</button>
                </div>
            </form>
        </div>
    </div>
</x-plantilla>