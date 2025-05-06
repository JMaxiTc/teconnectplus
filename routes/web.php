<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function(){
    return view('home', ["breadcrumbs" => []]);
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    // Rutas para login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Rutas para registro
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Aquí puedes añadir otras rutas que requieran autenticación
    // como las rutas para asesorías y materiales
});
