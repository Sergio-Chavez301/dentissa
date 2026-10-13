<x-plantilla>
    <x-slot:title>Nueva Oferta | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold mb-1" style="color: #1e1e24;">Nueva oferta</h1>
            <p class="text-muted mb-0">Agrega una promoción para mostrarla en la home pública.</p>
        </div>
        <a href="{{ route('admin.ofertas.index') }}" class="btn btn-light border py-2 px-3 fw-semibold text-muted">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>

    <div class="card shadow-sm border-0 bg-white p-4">
        <form action="{{ route('admin.ofertas.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Título</label>
                    <input type="text" name="titulo" class="form-control" value="{{ old('titulo') }}" required>
                    @error('titulo') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Etiqueta</label>
                    <input type="text" name="tag" class="form-control" value="{{ old('tag', 'Promoción') }}" placeholder="Oferta del mes">
                    @error('tag') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Precio</label>
                    <input type="number" step="0.01" min="0" name="precio" class="form-control" value="{{ old('precio') }}" placeholder="1299">
                    @error('precio') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Orden</label>
                    <input type="number" min="0" name="orden" class="form-control" value="{{ old('orden', 0) }}">
                    @error('orden') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>

                <div class="col-md-4 d-flex align-items-end">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="activo" value="1" id="activoOferta" checked>
                        <label class="form-check-label fw-semibold" for="activoOferta">Mostrar en la web</label>
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="5" required>{{ old('descripcion') }}</textarea>
                    @error('descripcion') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.ofertas.index') }}" class="btn btn-light border text-muted">Cancelar</a>
                <button type="submit" class="btn text-white border-0 bg-dentissa fw-semibold px-4">Guardar oferta</button>
            </div>
        </form>
    </div>
</x-plantilla>
