<nav class="col-md-3 col-lg-2 d-md-block bg-white border-end sidebar collapse" id="sidebarMenu" style="height: 100vh; position: sticky; top: 0; z-index: 1000;">
    <div class="position-sticky pt-3 d-flex flex-column h-100 px-3" style="overflow-y: auto; max-height: 100vh; scrollbar-width: thin; -ms-overflow-style: none;">
        
        <!-- Botón para cerrar en móviles -->
        <div class="d-flex d-md-none justify-content-end mb-2">
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Cerrar menú">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <a href="#" class="d-flex flex-column align-items-center mb-4 text-decoration-none py-2 text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Melissa López" class="img-fluid mb-2" style="max-height: 75px;">
            <span class="fs-5 fw-bold mb-0 text-break" style="letter-spacing: 0.5px; color: #1e1e24;">Melissa López N.</span>
            <small class="text-uppercase fw-semibold" style="color: #d75078; font-size: 0.65rem; letter-spacing: 1.5px;">Odontología Integral</small>
        </a>

        <hr style="background-color: #e0e0e0; opacity: 0.8; margin-top: 0;">

        <ul class="nav nav-pills flex-column mb-auto">
            
            @if(Auth::user()->role_id == 3)
                <!-- ================= MENÚ EXCLUSIVO PARA PACIENTES (role_id == 3) ================= -->
                
                <!-- Inicio Paciente -->
                <li class="nav-item mb-2">
                    <a href="{{ route('dashboard.paciente') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('dashboard.paciente') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-house-door me-2 fs-5"></i> <span>Inicio</span>
                    </a>
                </li>
                
                <!-- Pacientes (Muestra su propio perfil/expediente) -->
                <li class="mb-2">
                    @php($pacienteLogueado = App\Models\Patient::where('email', Auth::user()->email)->first())
                    <a href="{{ $pacienteLogueado ? route('patients.show', $pacienteLogueado->id) : '#' }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('patients*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-person-badge me-2 fs-5"></i> <span>Pacientes</span>
                    </a>
                </li>
                
                <!-- Citas (Sus citas) -->
                <li class="mb-2">
                    <a href="{{ route('citas.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('citas*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-calendar-event me-2 fs-5"></i> <span>Citas</span>
                    </a>
                </li>

                <!-- Casos Clínicos (Sus casos) -->
                <li class="mb-2">
                    <a href="{{ route('casos.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('casos*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-file-earmark-medical me-2 fs-5"></i> <span>Casos Clínicos</span>
                    </a>
                </li>

            @else
                <!-- ================= MENÚ DE ADMINISTRADOR Y ASISTENTE ================= -->
                
                <!-- Inicio -->
                <li class="nav-item mb-2">
                    <a href="{{ Auth::user()->role_id == 1 ? route('dashboard.admin') : route('dashboard.asistente') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('dashboard.*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-house-door me-2 fs-5"></i> <span>Inicio</span>
                    </a>
                </li>
                
                <!-- Módulos Operativos -->
                <li class="mb-2">
                    <a href="{{ route('patients.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('patients*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-person-badge me-2 fs-5"></i> <span>Pacientes</span>
                    </a>
                </li>
                
                <li class="mb-2">
                    <a href="{{ route('citas.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('citas*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-calendar-event me-2 fs-5"></i> <span>Citas</span>
                    </a>
                </li>

                <li class="mb-2">
                    <a href="{{ route('casos.index')}}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('casos*') ? 'active-dentissa' : 'link-hover' }}">
                        <i class="bi bi-file-earmark-medical me-2 fs-5"></i> <span>Casos Clínicos</span>
                    </a>
                </li>

                <!-- Módulos Exclusivos de Admin (role_id == 1) -->
                @if(Auth::user()->role_id == 1)
                    <li class="mb-2">
                        <a href="{{ route('users.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('users*') ? 'active-dentissa' : 'link-hover' }}">
                            <i class="bi bi-people me-2 fs-5"></i> <span>Usuarios</span>
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="{{ route('solicitudes.index')}}" class="nav-link text-dark d-flex align-items-center justify-content-between py-2 px-3 {{ request()->routeIs('solicitudes*') ? 'active-dentissa' : 'link-hover' }}">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope-check me-2 fs-5"></i> <span>Solicitudes Web</span>
                            </div>
                            @if(isset($nuevasSolicitudes) && $nuevasSolicitudes > 0)
                                <span class="badge rounded-pill" style="background-color: #d75078; font-size: 0.65rem;">{{ $nuevasSolicitudes }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <li class="mb-2">
                        <a href="{{ route('servicios.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('servicios*') ? 'active-dentissa' : 'link-hover' }}">
                            <i class="bi bi-gear-fill me-2 fs-5"></i> <span>Servicios</span>
                        </a>
                    </li>

                    <!-- Contenido Web -->
                    <li class="mb-2">
                        <a class="nav-link text-dark d-flex align-items-center justify-content-between py-2 px-3 link-hover" data-bs-toggle="collapse" href="#menuSitioWeb" role="button" aria-expanded="false">
                            <div class="d-flex align-items-center"><i class="bi bi-browser-edge me-2 fs-5"></i> <span>Contenido Web</span></div>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </a>
                        <div class="collapse ps-3" id="menuSitioWeb">
                            <ul class="list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Promociones</a></li>
                                <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Galería</a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Reportes -->
                    <li class="mb-2">
                        <a class="nav-link text-dark d-flex align-items-center justify-content-between py-2 px-3 link-hover" data-bs-toggle="collapse" href="#menuReportes" role="button" aria-expanded="false">
                            <div class="d-flex align-items-center"><i class="bi bi-file-earmark-bar-graph me-2 fs-5"></i> <span>Reportes</span></div>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </a>
                        <div class="collapse ps-3" id="menuReportes">
                            <ul class="list-unstyled fw-normal pb-1 small">
                                <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Reporte de Citas</a></li>
                                <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Ventas</a></li>
                            </ul>
                        </div>
                    </li>
                @endif
            @endif
        </ul>

        <hr style="background-color: #e0e0e0; opacity: 0.8; margin-top: 15px; margin-bottom: 10px;">

        <div class="dropdown pb-4">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle py-2 px-1 text-truncate" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2" style="color: #d75078;"></i>
                <strong class="text-truncate">{{ Auth::user()->nombre }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-light text-small shadow" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="#">Mi Perfil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    <a class="dropdown-item text-danger fw-bold" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>