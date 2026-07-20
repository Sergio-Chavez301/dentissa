@props(['citas', 'pacientes', 'casos', 'servicios'])
<div class="row g-3 mb-4">
    <!-- Tarjeta 1: Citas del Día -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #d75078 !important;">
            <div class="card-body d-flex align-items-center">
                <div class="badge rounded-pill bg-light p-3 text-dark me-3">
                    <i class="bi bi-calendar-check fs-4" style="color: #d75078;"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1 fs-7 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Citas de Hoy</h6>
                    <h4 class="card-title mb-0 fw-bold">{{ $citas }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta 2: Pacientes Totales -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #f2b0a6 !important;">
            <div class="card-body d-flex align-items-center">
                <div class="badge rounded-pill bg-light p-3 text-dark me-3">
                    <i class="bi bi-people fs-4" style="color: #f2b0a6;"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1 fs-7 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Pacientes</h6>
                    <h4 class="card-title mb-0 fw-bold">{{ $pacientes }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta 3: Casos Activos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #1e1e24 !important;">
            <div class="card-body d-flex align-items-center">
                <div class="badge rounded-pill bg-light p-3 text-dark me-3">
                    <i class="bi bi-file-earmark-medical fs-4" style="color: #1e1e24;"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1 fs-7 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Casos Activos</h6>
                    <h4 class="card-title mb-0 fw-bold">{{ $casos }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjeta 4: Servicios Ofrecidos -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm" style="border-left: 4px solid #d75078 !important;">
            <div class="card-body d-flex align-items-center">
                <div class="badge rounded-pill bg-light p-3 text-dark me-3">
                    <i class="bi bi-activity fs-4" style="color: #d75078;"></i>
                </div>
                <div>
                    <h6 class="card-subtitle text-muted mb-1 fs-7 text-uppercase fw-bold" style="letter-spacing: 0.5px;">Servicios</h6>
                    <h4 class="card-title mb-0 fw-bold">{{ $servicios }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>