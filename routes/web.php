<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\PatientController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\solicitudController;
use App\Http\Controllers\CasoClinicoController;
use App\Http\Controllers\ServicioController;

// --- VISTAS PÚBLICAS ---
Route::get('/', function () {
    return view('home');
})->name('home');

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

    Route::get('/dashboard/asistente', function () {
        return view('dashboard.asistente');
    })->name('dashboard.asistente');

    Route::get('/dashboard/paciente', function () {
        return view('dashboard.paciente');
    })->name('dashboard.paciente');

    // Perfil del usuario autenticado
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // CRUD de Usuarios del sistema
    Route::resource('users', UserController::class);

    // CRUD de Pacientes 
    Route::resource('patients', PatientController::class);

    //CRUD de Citas
    Route::resource('citas', CitaController::class);
        //disponivilidad de citas
    Route::get('/api/disponibilidad', [CitaController::class, 'verificarDisponibilidad'])->name('api.disponibilidad');

    //CRUD de Solicitudes
    Route::resource('solicitudes', SolicitudController::class);

    //CRUD de Casos Clinicos
    Route::resource('casos', CasoClinicoController::class);

    //CRUD de Servicios
    Route::resource('servicios', ServicioController::class);


    // Cerrar Sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});