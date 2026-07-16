<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse collapse-horizontal" id="sidebarMenu" style="background-color: #ffffff; border-right: 1px solid #e0e0e0;">
    <div class="position-sticky pt-3 d-flex flex-column h-100 px-3">
        <a href="#" class="d-flex flex-column align-items-center mb-4 text-decoration-none py-2 text-center">
            <img src="{{ asset('img/logo.png') }}" alt="Logo Melissa López" class="img-fluid mb-2" style="max-height: 75px;">
            <span class="fs-5 fw-bold mb-0" style="letter-spacing: 0.5px; color: #1e1e24;">Melissa López N.</span>
            <small class="text-uppercase fw-semibold" style="color: #d75078; font-size: 0.65rem; letter-spacing: 1.5px;">Odontología Integral</small>
        </a>

        <hr style="background-color: #e0e0e0; opacity: 0.8;">

        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="{{ route('dashboard.admin') }}" class="nav-link text-dark d-flex align-items-center py-2 px-3 {{ request()->routeIs('dashboard.admin') ? 'active-dentissa' : 'link-hover' }}">
                    <i class="bi bi-house-door me-2 fs-5"></i> <span>Inicio</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="nav-link text-dark d-flex align-items-center py-2 px-3 link-hover">
                    <i class="bi bi-people me-2 fs-5"></i> <span>Usuarios</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="nav-link text-dark d-flex align-items-center py-2 px-3 link-hover">
                    <i class="bi bi-calendar-event me-2 fs-5"></i> <span>Citas</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="nav-link text-dark d-flex align-items-center py-2 px-3 link-hover">
                    <i class="bi bi-file-earmark-medical me-2 fs-5"></i> <span>Casos Clínicos</span>
                </a>
            </li>
            <li class="mb-2">
                <a href="#" class="nav-link text-dark d-flex align-items-center py-2 px-3 link-hover">
                    <i class="bi bi-gear-fill me-2 fs-5"></i> <span>Servicios</span>
                </a>
            </li>
        </ul>

        <hr style="background-color: #e0e0e0; opacity: 0.8;">

        <div class="dropdown pb-4">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle py-2 px-1" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle fs-4 me-2" style="color: #d75078;"></i>
                <strong>{{ Auth::user()->nombre }}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-light text-small shadow" aria-labelledby="dropdownUser" style="background-color: #ffffff; border: 1px solid #e0e0e0;">
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