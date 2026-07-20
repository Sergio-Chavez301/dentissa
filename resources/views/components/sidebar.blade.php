<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse collapse-horizontal" id="sidebarMenu" style="background-color: #ffffff; border-right: 1px solid #e0e0e0; height: 100vh; position: sticky; top: 0;">
    <!-- d-flex flex-column y overflow-y-auto en este contenedor permiten el scroll interno de todo el sidebar -->
    <div class="position-sticky pt-3 d-flex flex-column h-100 px-3" style="overflow-y: auto; max-height: 100vh; scrollbar-width: thin; -ms-overflow-style: none;">
        
        <a href="#" class="d-flex flex-column align-items-center mb-4 text-decoration-none py-2 text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Melissa López" class="img-fluid mb-2" style="max-height: 75px;">
            <span class="fs-5 fw-bold mb-0" style="letter-spacing: 0.5px; color: #1e1e24;">Melissa López N.</span>
            <small class="text-uppercase fw-semibold" style="color: #d75078; font-size: 0.65rem; letter-spacing: 1.5px;">Odontología Integral</small>
        </a>

        <hr style="background-color: #e0e0e0; opacity: 0.8; margin-top: 0;">

        <!-- El menú principal -->
        <ul class="nav nav-pills flex-column mb-auto">
            <!-- 1. Inicio -->
            <li class="nav-item mb-2">
                <a href="{{ route('dashboard.admin') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('dashboard.admin') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-house-door me-2 fs-5"></i> <span>Inicio</span>
                </a>
            </li>
            
            <!-- 2. Usuarios -->
            <li class="mb-2">
                <a href="{{ route('users.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('users*') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-people me-2 fs-5"></i> <span>Usuarios</span>
                </a>
            </li>

            <li class="mb-2">
                <a href="{{ route('patients.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('patients*') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-person-badge me-2 fs-5"></i> <span>Pacientes</span>
                </a>
            </li>
            
            <!-- 3. Citas -->
            <li class="mb-2">
                <a href="{{ route('citas.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('citas*') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-calendar-event me-2 fs-5"></i> <span>Citas</span>
                </a>
            </li>

            <!-- 4. Solicitudes Web -->
            <li class="mb-2">
                <a href="{{ route('solicitudes.index')}}" class="nav-link text-dark d-flex align-items-center justify-content-between py-2 px-3 {{ request()->routeIs('solicitudes*') ? 'active-dentissa' : 'link-hover' }}">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope-check me-2 fs-5"></i> <span>Solicitudes Web</span>
                    </div>
                    
                    @if($nuevasSolicitudes > 0)
                        <span class="badge rounded-pill" style="background-color: #d75078; font-size: 0.65rem;">
                            {{ $nuevasSolicitudes }} Nuevas
                        </span>
                    @endif
                </a>
            </li>
            
            <!-- 5. Casos Clínicos -->
            <li class="mb-2">
                <a href="{{ route('casos.index')}}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('casos*') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-file-earmark-medical me-2 fs-5"></i> <span>Casos Clínicos</span>
                </a>
            </li>
            
            <!-- 6. Servicios -->
            <li class="mb-2">
                <a href="{{ route('servicios.index') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('servicios*') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-gear-fill me-2 fs-5"></i> <span>Servicios</span>
                </a>
            </li>

            <!-- 7. Contenido Web (Desplegable) -->
            <li class="mb-2">
                <a class="nav-link text-dark d-flex align-items-center justify-content-between py-2 px-3 link-hover" 
                   data-bs-toggle="collapse" href="#menuSitioWeb" role="button" aria-expanded="false" aria-controls="menuSitioWeb">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-browser-edge me-2 fs-5"></i> <span>Contenido Web</span>
                    </div>
                    <i class="bi bi-chevron-down small text-muted"></i>
                </a>
                <div class="collapse ps-3" id="menuSitioWeb">
                    <ul class="list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Ofertas y Promociones</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Imágenes Publicitarias</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Testimonios</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Misión, Visión y FAQ</a></li>
                    </ul>
                </div>
            </li>

            <!-- 8. Reportes (Desplegable) -->
            <li class="mb-2">
                <a class="nav-link text-dark d-flex align-items-center justify-content-between py-2 px-3 link-hover" 
                   data-bs-toggle="collapse" href="#menuReportes" role="button" aria-expanded="false" aria-controls="menuReportes">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-file-earmark-bar-graph me-2 fs-5"></i> <span>Reportes</span>
                    </div>
                    <i class="bi bi-chevron-down small text-muted"></i>
                </a>
                <div class="collapse ps-3" id="menuReportes">
                    <ul class="list-unstyled fw-normal pb-1 small">
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Reporte de Pacientes</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Reporte de Citas</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Historial Clínico</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Servicios y Ventas</a></li>
                        <li><a href="#" class="nav-link text-dark py-1 px-3 my-1 link-hover rounded">Cancelaciones</a></li>
                    </ul>
                </div>
            </li>
        </ul>

        <hr style="background-color: #e0e0e0; opacity: 0.8; margin-top: 15px; margin-bottom: 10px;">

        <!-- Dropdown de Usuario -->
        <div class="dropdown pb-4">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle py-2 px-1" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2" style="color: #d75078;"></i>
                <strong>{{ Auth::user()->nombre }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-light text-small shadow" aria-labelledby="dropdownUser" style="background-color: #ffffff; border: 1px solid #e0e0e0; z-index: 1050;">
                <li><a class="dropdown-item link-hover-drop" href="#">Mi Perfil</a></li>
                <li><hr class="dropdown-divider" style="background-color: #e0e0e0; opacity: 0.5;"></li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="dropdown-item text-danger fw-bold" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>