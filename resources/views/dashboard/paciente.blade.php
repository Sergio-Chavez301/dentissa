<x-plantilla>
    <x-slot:title>Panel del paciente</x-slot:title>

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Panel del paciente</h1>
            <p class="text-muted">Consulta tus próximos tratamientos y estado de tu atención.</p>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
            <span class="badge bg-dentissa py-2 px-3 fs-6 rounded-pill shadow-sm text-white">
                <i class="bi bi-calendar3 me-1"></i> {{ now()->format('d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 p-4 mb-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-clipboard2-pulse text-dentissa me-2"></i>Próximos tratamientos</h5>
                <ul class="list-group list-group-flush">
                    @php($paciente = App\Models\Patient::where('email', Auth::user()->email)->first())
                    @php($casos = $paciente ? App\Models\CasoClinico::where('paciente_id', $paciente->id)->take(3)->get() : collect())
                    @forelse($casos as $caso)
                        <li class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $caso->tratamiento_base }}</div>
                                    <small class="text-muted">Progreso: {{ $caso->progreso }}%</small>
                                </div>
                                <span class="badge rounded-pill bg-success-subtle text-success">{{ ucfirst($caso->estado) }}</span>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">No hay tratamientos registrados por el momento.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-info-circle text-dentissa me-2"></i>Tu información</h5>
                <p class="mb-2"><strong>Nombre:</strong> {{ Auth::user()->nombre }} {{ Auth::user()->apellidos }}</p>
                <p class="mb-0"><strong>Correo:</strong> {{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>
</x-plantilla>