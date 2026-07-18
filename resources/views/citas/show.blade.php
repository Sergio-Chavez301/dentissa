<x-plantilla>
    <x-slot:title>Detalle de Cita | Dentissa</x-slot:title>

    <div class="pb-2 mb-4 border-bottom">
        <a href="{{ route('citas.index') }}" class="text-muted small text-decoration-none">← Volver</a>
        <h1 class="h2 fw-bold mt-2" style="color: #1e1e24;">Detalle de la Cita #{{ $cita->id }}</h1>
    </div>

    <div class="card p-4 shadow-sm border-0 bg-white" style="max-width: 500px;">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Paciente:</strong> {{ $cita->paciente->nombre }} {{ $cita->paciente->apellidos }}</li>
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Tratamiento:</strong> {{ $cita->servicio->nombre }}</li>
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Fecha:</strong> {{ $cita->fecha }}</li>
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Hora:</strong> {{ $cita->hora }}</li>
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Estado:</strong> {{ ucfirst($cita->estado) }}</li>
        </ul>
        <div class="mt-4 text-end">
            <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-outline-secondary">Editar</a>
        </div>
    </div>
</x-plantilla>