<x-plantilla>
    <x-slot:title>Contenido Web | Dentissa</x-slot:title>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold mb-1" style="color: #1e1e24;">Contenido Web</h1>
            <p class="text-muted mb-0">Administra las ofertas que se muestran en la página pública de inicio.</p>
        </div>
        <a href="{{ route('admin.ofertas.create') }}" class="btn text-white border-0 py-2 px-3 fw-semibold bg-dentissa shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Nueva oferta
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <th>Título</th>
                        <th>Etiqueta</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ofertas as $oferta)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $oferta->titulo }}</td>
                            <td><span class="badge rounded-pill text-dark" style="background-color: rgba(215,80,120,.12);">{{ $oferta->tag ?? 'Promoción' }}</span></td>
                            <td class="fw-medium text-dark">
                                @if($oferta->precio)
                                    ${{ number_format((float) $oferta->precio, 2) }}
                                @else
                                    <span class="text-muted">Consulta</span>
                                @endif
                            </td>
                            <td>
                                @if($oferta->activo)
                                    <span class="badge bg-success-subtle text-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary">Oculta</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('admin.ofertas.edit', $oferta) }}" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil-fill text-muted"></i>
                                    </a>
                                    <form action="{{ route('admin.ofertas.destroy', $oferta) }}" method="POST" onsubmit="return confirm('¿Deseas eliminar esta oferta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border" title="Eliminar">
                                            <i class="bi bi-trash-fill text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-tag fs-2 d-block mb-2 text-light"></i>
                                Aún no hay ofertas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>
