<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsesoriasController;
use App\Http\Controllers\AsesorMateriaController;

Route::get('/', function(){
    return app(CatalogosController::class)->index();
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

    Route::get('/catalogos/materias', [CatalogosController::class, 'materiasGet']);
    Route::get('/catalogos/materias/agregar', [CatalogosController::class, 'materiasAgregarGet']);
    Route::post('/catalogos/materias/agregar', [CatalogosController::class, 'materiasAgregarPost']);
    Route::get('/catalogos/materias/{id}/actualizar', [CatalogosController::class, 'materiasActualizarGet']);
    Route::post('/catalogos/materias/{id}/actualizar', [CatalogosController::class, 'materiasActualizarPost']);
    Route::get('/materiales', [CatalogosController::class, 'materialesTodosGet'])->name('materiales.todos');
    Route::get('/catalogos/materias/{idMateria}/materiales', [CatalogosController::class, 'materialesGet'])->name('materiales.get');
    Route::post('/materiales/guardar', [CatalogosController::class, 'guardar'])->name('materiales.guardar');

    Route::get('perfil', [UserController::class, 'perfil'])->name('perfil.show');
    Route::post('perfil/{id_usuario}/actualizar', [UserController::class, 'actualizar'])->name('perfil.update');
    

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

//Asesor mis materias
Route::middleware('auth')->group(function () {
    Route::get('asesor/mis-materias', [AsesorMateriaController::class, 'misMateriasGet'])->name('misMateriasGet');
    Route::post('asesor/asignar-materia', [AsesorMateriaController::class, 'asignarMateriaPost'])->name('asignarMateriaPost');
    Route::delete('asesor/eliminar-materia/{id}', [AsesorMateriaController::class, 'eliminarMateriaPost'])->name('eliminarMateriaPost');
});



// Estudiante
Route::middleware(['auth'])->group(function () {
    Route::get('/asesorias/solicitar', [AsesoriasController::class, 'estudianteSolicitarGet'])->name('asesorias.solicitar.get');
    Route::post('/asesorias/solicitar', [AsesoriasController::class, 'estudianteSolicitarPost'])->name('asesorias.solicitar.post');
    Route::get('/asesorias/asesores/{id_materia}', [AsesoriasController::class, 'asesoresPorMateria'])->name('asesorias.asesores');
});

// Asesor
Route::middleware(['auth'])->group(function () {
    Route::get('/asesorias/solicitudes', [AsesoriasController::class, 'asesorSolicitudesGet'])->name('asesorias.solicitudes.get');
    Route::post('/asesorias/actualizar/{id}', [AsesoriasController::class, 'actualizarEstado'])->name('asesorias.actualizar');
});

