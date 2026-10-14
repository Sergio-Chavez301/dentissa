<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\PatientController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\CasoClinicoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HorarioBloqueadoController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OfertaController;

// --- VISTAS PÚBLICAS ---
Route::get('/', function () {
    return view('home', [
        'ofertas' => \App\Models\Oferta::where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->get(),
    ]);
})->name('home');
Route::get('/acerca-de', function(){
    return view('acerca-de');
})->name('acerca-de');
Route::get('/ofertas', [OfertaController::class, 'publicIndex'])->name('ofertas.public');
// Ruta pública para que el prospecto envíe su solicitud de cita
Route::post('/solicitud-cita', [SolicitudController::class, 'storePublic'])->name('solicitud.storePublic');

// Disponibilidad de citas (Pública para que cargue dinámicamente en el modal)
Route::get('/api/disponibilidad', [CitaController::class, 'verificarDisponibilidad'])->name('api.disponibilidad');

// --- AUTENTICACIÓN (Solo Invitados) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// --- RUTAS PROTEGIDAS (Solo Autenticados y sin caché de historial) ---
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    
    // Dashboards divididos por rol
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');
    Route::get('/dashboard/asistente', [AdminController::class, 'asistente'])->name('dashboard.asistente');
    Route::get('/reportes', [ReportController::class, 'index'])->name('reportes.index');
    
    // Panel del paciente vinculado al PatientController
    Route::get('/dashboard/paciente', [PatientController::class, 'dashboard'])->name('dashboard.paciente');

    // Perfil del usuario autenticado
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // CRUDs
    Route::resource('users', UserController::class);
    Route::resource('patients', PatientController::class);
    
    // Ruta personalizada para generar y enviar credenciales de WhatsApp a pacientes existentes
    Route::get('/patients/{id}/credenciales-whatsapp', [PatientController::class, 'enviarCredencialesWhatsApp'])->name('patients.whatsapp');

    // Ruta para generar una nueva contraseña de acceso al paciente
    Route::post('/patients/{patient}/reset-password', [PatientController::class, 'resetPassword'])->name('patients.reset-password');

    Route::resource('citas', CitaController::class);
    Route::resource('solicitudes', SolicitudController::class);
    Route::resource('casos', CasoClinicoController::class);
    Route::get('/casos/{caso}/citas/create', [CitaController::class, 'createForCase'])->name('casos.citas.create');
    Route::post('/casos/{caso}/citas', [CitaController::class, 'storeForCase'])->name('casos.citas.store');
    Route::resource('servicios', ServicioController::class);

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('ofertas', OfertaController::class);
    });

    Route::get('/horarios-bloqueados', [HorarioBloqueadoController::class, 'index'])->name('horarios-bloqueados.index');
    Route::post('/horarios-bloqueados', [HorarioBloqueadoController::class, 'store'])->name('horarios-bloqueados.store');
    Route::delete('/horarios-bloqueados/{horarioBloqueado}', [HorarioBloqueadoController::class, 'destroy'])->name('horarios-bloqueados.destroy');

    // Cerrar Sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});