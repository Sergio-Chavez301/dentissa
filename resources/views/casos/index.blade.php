<x-plantilla>
    <x-slot:title>Casos Clínicos | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Casos Clínicos</h1>
            <p class="text-muted">Gestión y seguimiento de tratamientos.</p>
        </div>
        <a href="{{ route('casos.create') }}" class="btn text-white bg-dentissa shadow-sm px-3">
            <i class="bi bi-plus-lg me-1"></i>Nuevo Caso
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white">
        <table class="table align-middle table-hover">
            <thead>
                <tr class="text-muted" style="font-size: 0.85rem;">
                    <th>Paciente</th>
                    <th>Tratamiento</th>
                    <th>Progreso</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($casos as $caso)
                <tr>
                    <td class="fw-semibold text-dark">{{ $caso->paciente->nombre }} {{ $caso->paciente->apellidos }}</td>
                    <td>{{ $caso->tratamiento_base }}</td>
                    <td style="width: 200px;">
                        <div class="progress" style="height: 8px;"><div class="progress-bar bg-primary" style="width: {{ $caso->progreso }}%;"></div></div>
                        <small class="text-muted">{{ $caso->progreso }}%</small>
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst($caso->estado) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('casos.show', $caso->id) }}" class="btn btn-sm btn-light border"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('casos.edit', $caso->id) }}" class="btn btn-sm btn-light border"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-plantilla>