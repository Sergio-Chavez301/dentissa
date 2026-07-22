<x-plantilla>
    <x-slot:title>Gestión de Citas | Dentissa</x-slot:title>

    <div class="d-flex justify-content-between align-items-center pb-2 mb-4 border-bottom">
        <div>
            <h1 class="h2 fw-bold" style="color: #1e1e24;">Citas Programadas</h1>
            <p class="text-muted">Administra el calendario de atenciones de la clínica.</p>
        </div>
        
        {{-- Ocultar el botón "Nueva Cita" si el usuario es un paciente (role_id == 3) --}}
        @if(auth()->check() && auth()->user()->role_id != 3)
            <div>
                <a href="{{ route('citas.create') }}" class="btn text-white border-0 py-2 px-3 fw-semibold bg-dentissa shadow-sm">
                    <i class="bi bi-calendar-plus-fill me-2"></i>Nueva Cita
                </a>
            </div>
        @endif
    </div>

    <!-- ALERTA DE CREDENCIALES Y WHATSAPP (PACIENTE NUEVO) -->
    @if(session('temp_password') && session('temp_telefono'))
        @php
            $telefonoLimpio = preg_replace('/[^0-9]/', '', session('temp_telefono'));
            $mensaje = "Hola " . session('temp_nombre') . ", tu cuenta en Dentissa ha sido creada con éxito. Tus credenciales de acceso son:\nUsuario: " . session('temp_username') . "\nContraseña: " . session('temp_password') . "\nPuedes iniciar sesión en: " . url('/login');
            $urlWhatsApp = "https://wa.me/" . $telefonoLimpio . "?text=" . urlencode($mensaje);
        @endphp

        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4 border-0 d-flex justify-content-between align-items-center" role="alert">
            <div class="pe-4">
                <i class="bi bi-whatsapp me-2"></i><strong>¡Cuenta creada con éxito!</strong> 
                Usuario: <code class="fw-bold bg-white text-dark px-2 py-1 rounded mx-1">{{ session('temp_username') }}</code>
                Contraseña: <code class="fw-bold bg-white text-dark px-2 py-1 rounded mx-1">{{ session('temp_password') }}</code>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ $urlWhatsApp }}" target="_blank" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-whatsapp"></i> Enviar por WhatsApp
                </a>
                <button type="button" class="btn-close position-relative p-2" data-bs-dismiss="alert" aria-label="Close" style="top: 0; right: 0;"></button>
            </div>
        </div>

        @php
            session()->forget(['temp_password', 'temp_telefono', 'temp_nombre', 'temp_username']);
        @endphp
    @endif

    <!-- Alertas de Éxito Generales -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card p-4 shadow-sm border-0 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr class="text-muted" style="font-size: 0.85rem;">
                        <th>Paciente</th>
                        <th>Servicio</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        
                        {{-- Ocultar el encabezado de "Acciones" si es paciente --}}
                        @if(auth()->check() && auth()->user()->role_id != 3)
                            <th class="text-end">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($citas as $cita)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $cita->paciente->nombre ?? 'N/A' }} {{ $cita->paciente->apellidos ?? '' }}</td>
                            <td>{{ $cita->servicio->nombre ?? 'N/A' }}</td>
                            <td>
                                <i class="bi bi-calendar-event me-1 text-muted"></i> {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                                <br>
                                <i class="bi bi-clock me-1 text-muted"></i> {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}
                            </td>
                            <td>
                                @switch($cita->estado)
                                    @case(\App\Models\Cita::ESTADO_EN_ESPERA)
                                        <span class="badge rounded-pill bg-warning-subtle text-warning px-2.5 py-1">En Espera</span>
                                        @break
                                    @case(\App\Models\Cita::ESTADO_REALIZADA)
                                        <span class="badge rounded-pill bg-success-subtle text-success px-2.5 py-1">Realizada</span>
                                        @break
                                    @case(\App\Models\Cita::ESTADO_NO_PRESENTO)
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary px-2.5 py-1">No se presentó</span>
                                        @break
                                    @case(\App\Models\Cita::ESTADO_CANCELADA)
                                        <span class="badge rounded-pill bg-danger-subtle text-danger px-2.5 py-1">Cancelada</span>
                                        @break
                                    @default
                                        <span class="badge rounded-pill bg-light text-dark px-2.5 py-1">{{ ucfirst($cita->estado) }}</span>
                                @endswitch
                            </td>
                           
                           {{-- Ocultar los botones de acciones si es paciente --}}
                           @if(auth()->check() && auth()->user()->role_id != 3)
                               <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <!-- Botón Ver -->
                                    <a href="{{ route('citas.show', $cita->id) }}" class="btn btn-sm btn-light border" title="Ver Detalles">
                                        <i class="bi bi-eye-fill text-muted"></i>
                                    </a>
                                    
                                    <!-- Botón Editar -->
                                    <a href="{{ route('citas.edit', $cita->id) }}" class="btn btn-sm btn-light border" title="Editar">
                                        <i class="bi bi-pencil-fill text-muted"></i>
                                    </a>

                                    <!-- Acciones rápidas (Solo si está en espera) -->
                                    @if($cita->estado === \App\Models\Cita::ESTADO_EN_ESPERA)
                                        <!-- Formulario Realizada -->
                                        <form action="{{ route('citas.update', $cita->id) }}" method="POST" class="m-0">
                                            @csrf 
                                            @method('PUT')
                                            <input type="hidden" name="fecha" value="{{ $cita->fecha }}">
                                            <input type="hidden" name="hora" value="{{ $cita->hora }}">
                                            <input type="hidden" name="estado" value="{{ \App\Models\Cita::ESTADO_REALIZADA }}">
                                            <button type="submit" class="btn btn-sm btn-light border" title="Marcar Realizada">
                                                <i class="bi bi-check-lg text-success"></i>
                                            </button>
                                        </form>

                                        <!-- Formulario Cancelar -->
                                        <form action="{{ route('citas.update', $cita->id) }}" method="POST" class="m-0">
                                            @csrf 
                                            @method('PUT')
                                            <input type="hidden" name="fecha" value="{{ $cita->fecha }}">
                                            <input type="hidden" name="hora" value="{{ $cita->hora }}">
                                            <input type="hidden" name="estado" value="{{ \App\Models\Cita::ESTADO_CANCELADA }}">
                                            <button type="submit" class="btn btn-sm btn-light border" title="Cancelar">
                                                <i class="bi bi-x-lg text-danger"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->check() && auth()->user()->role_id == 3) ? 4 : 5 }}" class="text-center text-muted py-4">
                                <i class="bi bi-calendar-x fs-3 d-block mb-2 text-secondary"></i>
                                No hay citas programadas actualmente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-plantilla>