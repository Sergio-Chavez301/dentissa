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
use App\Http\Controllers\OfficeSettingController;

// --- VISTAS PÚBLICAS ---
Route::get('/', function () {
    return view('home', [
        'ofertas' => \App\Models\Oferta::where('activo', true)
            ->orderBy('orden')
            ->orderBy('id')
            ->get(),
            'officeSettings' => \App\Models\OfficeSetting::current(),
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
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:3,1')
        ->name('password.email');
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
    Route::resource('users', UserController::class)->middleware('admin');
    Route::resource('patients', PatientController::class);
    
    // Ruta personalizada para generar y enviar credenciales de WhatsApp a pacientes existentes
    Route::post('/patients/{id}/credenciales-whatsapp', [PatientController::class, 'enviarCredencialesWhatsApp'])->middleware('admin')->name('patients.whatsapp');
    Route::post('/patients/{id}/credentials', [PatientController::class, 'updateCredentials'])->middleware('admin')->name('patients.credentials.update');

    // Ruta para generar una nueva contraseña de acceso al paciente
    Route::post('/patients/{patient}/reset-password', [PatientController::class, 'enviarCredencialesWhatsApp'])->middleware('admin')->name('patients.reset-password');

    Route::resource('citas', CitaController::class);
    Route::post('/citas/{cita}/estado', [CitaController::class, 'updateStatus'])
        ->name('citas.status');
    Route::resource('solicitudes', SolicitudController::class);
    Route::resource('casos', CasoClinicoController::class);
    Route::get('/casos/{caso}/citas/create', [CitaController::class, 'createForCase'])->name('casos.citas.create');
    Route::post('/casos/{caso}/citas', [CitaController::class, 'storeForCase'])->name('casos.citas.store');
    Route::resource('servicios', ServicioController::class);

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('ofertas', OfertaController::class);
        Route::get('/consultorio', [OfficeSettingController::class, 'edit'])->name('office-settings.edit');
        Route::put('/consultorio', [OfficeSettingController::class, 'update'])->name('office-settings.update');
    });

    Route::get('/horarios-bloqueados', [HorarioBloqueadoController::class, 'index'])->name('horarios-bloqueados.index');
    Route::post('/horarios-bloqueados', [HorarioBloqueadoController::class, 'store'])->name('horarios-bloqueados.store');
    Route::delete('/horarios-bloqueados/{horarioBloqueado}', [HorarioBloqueadoController::class, 'destroy'])->name('horarios-bloqueados.destroy');

    // Cerrar Sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});