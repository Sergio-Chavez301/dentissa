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
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\MobileAppDocumentationController;
use App\Http\Controllers\AdminDocumentationController;

// --- VISTAS PÚBLICAS ---
Route::middleware('mobile-app-roles-only')->group(function () {
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
    Route::post('/solicitud-cita', [SolicitudController::class, 'storePublic'])->name('solicitud.storePublic');
    Route::post('/api/chatbot', [ChatbotController::class, 'respond'])
        ->middleware('throttle:20,1')
        ->name('api.chatbot');
    Route::get('/api/disponibilidad', [CitaController::class, 'verificarDisponibilidad'])->name('api.disponibilidad');
});
// --- AUTENTICACIÓN (Solo Invitados) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', function (\Illuminate\Http\Request $request) {
        if ($request->boolean('mobile')) {
            $request->session()->put('mobile_app_mode', true);
        }

        return view('auth.login', [
            'officeSettings' => \App\Models\OfficeSetting::current(),
        ]);
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->middleware('mobile-app-roles-only')->name('register');

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
    Route::get('/dashboard/admin', [AdminController::class, 'index'])
        ->middleware('admin')
        ->name('dashboard.admin');
    Route::get('/dashboard/asistente', [AdminController::class, 'asistente'])
        ->middleware('staff')
        ->name('dashboard.asistente');
    Route::get('/reportes', [ReportController::class, 'index'])
        ->middleware('staff')
        ->name('reportes.index');
    
    // Panel del paciente vinculado al PatientController
    Route::get('/dashboard/paciente', [PatientController::class, 'dashboard'])
        ->middleware('patient')
        ->name('dashboard.paciente');

    // Perfil del usuario autenticado
    Route::middleware('clinic-user')->group(function () {
        Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    });

    // CRUDs
    Route::resource('users', UserController::class)->middleware('admin');
    Route::resource('patients', PatientController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('staff');
    Route::resource('patients', PatientController::class)
        ->only(['show'])
        ->middleware('clinic-user');
    
    // Ruta personalizada para generar y enviar credenciales de WhatsApp a pacientes existentes
    Route::post('/patients/{id}/credenciales-whatsapp', [PatientController::class, 'enviarCredencialesWhatsApp'])->middleware('admin')->name('patients.whatsapp');
    Route::post('/patients/{id}/credentials', [PatientController::class, 'updateCredentials'])->middleware('admin')->name('patients.credentials.update');

    // Ruta para generar una nueva contraseña de acceso al paciente
    Route::post('/patients/{patient}/reset-password', [PatientController::class, 'enviarCredencialesWhatsApp'])->middleware('admin')->name('patients.reset-password');

    Route::resource('citas', CitaController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('staff');
    Route::resource('citas', CitaController::class)
        ->only(['index', 'show'])
        ->middleware('clinic-user');
    Route::post('/citas/{cita}/estado', [CitaController::class, 'updateStatus'])
        ->middleware('staff')
        ->name('citas.status');
    Route::resource('solicitudes', SolicitudController::class)->middleware('staff');
    Route::resource('casos', CasoClinicoController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('staff');
    Route::resource('casos', CasoClinicoController::class)
        ->only(['index', 'show'])
        ->middleware('clinic-user');
    Route::get('/casos/{caso}/citas/create', [CitaController::class, 'createForCase'])
        ->middleware('staff')
        ->name('casos.citas.create');
    Route::post('/casos/{caso}/citas', [CitaController::class, 'storeForCase'])
        ->middleware('staff')
        ->name('casos.citas.store');
    Route::middleware('patient')->group(function () {
        Route::get('/mis-tratamientos/{caso}/solicitar-cita', [CitaController::class, 'createForPatientCase'])->name('patients.treatment.request');
        Route::post('/mis-tratamientos/{caso}/solicitar-cita', [CitaController::class, 'storeForPatientCase'])->name('patients.treatment.store');
        Route::post('/citas/{cita}/cancelar', [CitaController::class, 'cancelForPatient'])->name('citas.cancel');
        Route::get('/mis-citas/solicitar', [CitaController::class, 'createForPatient'])->name('citas.patient.create');
        Route::post('/mis-citas/solicitar', [CitaController::class, 'storeForPatient'])->name('citas.patient.store');
    });
    Route::resource('servicios', ServicioController::class)->middleware('staff');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/documentacion', [AdminDocumentationController::class, 'index'])->name('documentation.index');
        Route::get('/documentacion/contenido/{document}', [AdminDocumentationController::class, 'content'])
            ->where('document', '(index|diagrama-base-datos|flujo-sistema|documentacion-sistema)\.html')
            ->name('documentation.content');
        Route::get('/documentacion/{document}', [AdminDocumentationController::class, 'show'])
            ->where('document', '(index|diagrama-base-datos|flujo-sistema|documentacion-sistema)\.html')
            ->name('documentation.show');
        Route::get('/aplicacion-movil', [MobileAppDocumentationController::class, 'show'])->name('mobile-app-guide');
        Route::resource('ofertas', OfertaController::class);
        Route::get('/consultorio', [OfficeSettingController::class, 'edit'])->name('office-settings.edit');
        Route::put('/consultorio', [OfficeSettingController::class, 'update'])->name('office-settings.update');
    });

    Route::middleware('admin')->group(function () {
        Route::get('/horarios-bloqueados', [HorarioBloqueadoController::class, 'index'])->name('horarios-bloqueados.index');
        Route::post('/horarios-bloqueados', [HorarioBloqueadoController::class, 'store'])->name('horarios-bloqueados.store');
        Route::delete('/horarios-bloqueados/{horarioBloqueado}', [HorarioBloqueadoController::class, 'destroy'])->name('horarios-bloqueados.destroy');
    });

    // Cerrar Sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});