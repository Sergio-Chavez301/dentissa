<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController; // Importamos el controlador del Admin

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
    // CAMBIO CLAVE: Ahora apunta al método index de tu AdminController para cargar la base de datos
    Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('dashboard.admin');

    Route::get('/dashboard/asistente', function () {
        return view('dashboard.asistente');
    })->name('dashboard.asistente');

    Route::get('/dashboard/paciente', function () {
        return view('dashboard.paciente');
    })->name('dashboard.paciente');

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});