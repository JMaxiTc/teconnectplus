<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\AdminController;

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

    Route::get('/', [CatalogosController::class, 'index']);
    Route::get('/catalogos/materias', [CatalogosController::class, 'materiasGet']);
    Route::get('/catalogos/materias/agregar', [CatalogosController::class, 'materiasAgregarGet']);
    Route::post('/catalogos/materias/agregar', [CatalogosController::class, 'materiasAgregarPost']);
    Route::get('/catalogos/materias/{id}/actualizar', [CatalogosController::class, 'materiasActualizarGet']);
    Route::post('/catalogos/materias/{id}/actualizar', [CatalogosController::class, 'materiasActualizarPost']);

    // Aquí puedes añadir otras rutas que requieran autenticación
    // como las rutas para asesorías y materiales
});
Route::middleware('auth','can:ADMIN')->group(function () {
    Route::get('/admin/usuarios', [AdminController::class, 'usuariosGet']);
    Route::get('/admin/usuarios/agregar', [AdminController::class, 'usuariosAgregarGet']);
    Route::post('/admin/usuarios/agregar', [AdminController::class, 'usuariosAgregarPost']);
    Route::get('/admin/usuarios/{id_usuario}/actualizar', [AdminController::class, 'usuariosActualizarGet']);
    Route::post('/admin/usuarios/{id_usuario}/actualizar', [AdminController::class, 'usuariosActualizarPost']);
});