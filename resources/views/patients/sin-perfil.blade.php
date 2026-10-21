<x-plantilla>
    <x-slot:title>Expediente no vinculado | Dentissa</x-slot:title>

    <section class="mx-auto py-5" style="max-width: 680px;">
        <div class="card border-0 shadow-sm p-4 p-md-5">
            <span class="text-muted small text-uppercase fw-bold">Panel de paciente</span>
            <h1 class="h3 fw-bold mt-2 mb-3">Tu expediente aún no está vinculado</h1>
            <p class="text-muted mb-4">Tu cuenta está activa, pero todavía no encontramos un expediente clínico asociado. Contacta al equipo de Dentissa para que pueda vincularlo y habilitar tu información.</p>
            <a class="btn btn-dentissa text-white align-self-start" href="{{ route('profile.show') }}">Revisar mis datos</a>
        </div>
    </section>
</x-plantilla>