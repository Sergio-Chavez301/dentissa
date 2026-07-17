<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\UserController; 
use App\Http\Controllers\SolicitudCitaController; // <-- IMPORTANTE: Importamos el controlador de solicitudes

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

    // CRUD de Usuarios del sistema (Personal de la clínica)
    Route::resource('users', UserController::class);

  /*  // --- NUEVO: Rutas para Solicitudes de Citas desde la Web ---
    Route::get('/dashboard/solicitudes', [SolicitudCitaController::class, 'index'])->name('solicitudes.index');
    Route::post('/dashboard/solicitudes/{id}/aprobar', [SolicitudCitaController::class, 'aprobar'])->name('solicitudes.aprobar');
    Route::post('/dashboard/solicitudes/{id}/rechazar', [SolicitudCitaController::class, 'rechazar'])->name('solicitudes.rechazar');
*/
    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});