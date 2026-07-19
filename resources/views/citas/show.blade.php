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
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</li>
            <li class="list-group-item d-flex justify-content-between px-0"><strong>Hora:</strong> {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}</li>
            
            <!-- Estado con lógica profesional -->
            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <strong>Estado:</strong>
                @switch($cita->estado)
                    @case(\App\Models\Cita::ESTADO_EN_ESPERA)
                        <span class="badge rounded-pill bg-warning-subtle text-warning">En Espera</span>
                        @break
                    @case(\App\Models\Cita::ESTADO_REALIZADA)
                        <span class="badge rounded-pill bg-success-subtle text-success">Realizada</span>
                        @break
                    @case(\App\Models\Cita::ESTADO_NO_PRESENTO)
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary">No se presentó</span>
                        @break
                    @case(\App\Models\Cita::ESTADO_CANCELADA)
                        <span class="badge rounded-pill bg-danger-subtle text-danger">Cancelada</span>
                        @break
                    @default
                        <span class="badge rounded-pill bg-light text-dark">{{ ucfirst($cita->estado) }}</span>
                @endswitch
            </li>
        </ul>
        
        <div class="mt-4 text-end">
            <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-outline-secondary">Editar</a>
        </div>
    </div>
</x-plantilla>