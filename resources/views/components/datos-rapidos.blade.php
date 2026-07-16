@props(['citas' => 0, 'pacientes' => 0, 'casos' => 0, 'servicios' => 0])

<div class="row g-3 mb-4">
    <!-- Citas de Hoy -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 shadow-sm bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Citas de Hoy</span>
                    <h3 class="fw-bold mb-0 mt-1" style="color: #1e1e24;">{{ $citas }}</h3>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fff0f3;">
                    <i class="bi bi-calendar-event fs-4 text-dentissa"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Pacientes Activos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 shadow-sm bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Pacientes Activos</span>
                    <h3 class="fw-bold mb-0 mt-1" style="color: #1e1e24;">{{ $pacientes }}</h3>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fff0f3;">
                    <i class="bi bi-people fs-4 text-dentissa"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Casos Activos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 shadow-sm bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Casos Activos</span>
                    <h3 class="fw-bold mb-0 mt-1" style="color: #1e1e24;">{{ $casos }}</h3>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fff0f3;">
                    <i class="bi bi-file-earmark-medical fs-4 text-dentissa"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Servicios Ofrecidos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 p-3 shadow-sm bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-uppercase text-muted fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.5px;">Servicios</span>
                    <h3 class="fw-bold mb-0 mt-1" style="color: #1e1e24;">{{ $servicios }}</h3>
                </div>
                <div class="p-3 rounded-circle" style="background-color: #fff0f3;">
                    <i class="bi bi-heartpulse fs-4 text-dentissa"></i>
                </div>
            </div>
        </div>
    </div>
</div>