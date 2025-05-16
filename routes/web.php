<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogosController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsesoriasController;
use App\Http\Controllers\AsesorMateriaController;
use App\Http\Controllers\VideollamadaController;
use App\Http\Controllers\CalificacionesController;

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
    
    // Ruta para guardar calificaciones
    Route::post('/calificaciones/guardar', [CalificacionesController::class, 'guardarCalificacion'])->name('calificaciones.guardar');

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
    
    // Ruta para refrescar el token CSRF
    Route::get('/csrf-token', function () {
        return response()->json(['token' => csrf_token()]);
    });
    

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

// Estudiante
Route::middleware(['auth', 'can:ESTUDIANTE'])->group(function () {
    // Solicitar asesoría
    Route::get('/asesorias/solicitar', [AsesoriasController::class, 'estudianteSolicitarGet'])->name('asesorias.solicitar.get');
    Route::post('/asesorias/solicitar', [AsesoriasController::class, 'estudianteSolicitarPost'])->name('asesorias.solicitar.post');
    Route::get('/asesorias/asesores/{id_materia}', [AsesoriasController::class, 'asesoresPorMateria'])->name('asesorias.asesores');
    
    // Asesorías Activas (CONFIRMADA, PROCESO) - Página principal
    Route::get('/asesorias', [AsesoriasController::class, 'estudianteAsesoriasActivasGet'])->name('asesorias.index');
    
    // Solicitudes pendientes (PENDIENTE)
    Route::get('/asesorias/pendientes', [AsesoriasController::class, 'estudianteSolicitudesPendientesGet'])->name('asesorias.pendientes.get');
    Route::get('/asesorias/pendientes/count', [AsesoriasController::class, 'countPendingSolicitudesEstudiante'])->name('asesorias.pendientes.count');
    Route::get('/asesorias/activas/count', [AsesoriasController::class, 'countActiveAsesoriasEstudiante'])->name('asesorias.activas.count');
    
    // Historial (CANCELADA, FINALIZADA)
    Route::get('/asesorias/historial', [AsesoriasController::class, 'estudianteHistorialGet'])->name('asesorias.historial.get');
    
    // Detalle de asesoría
    Route::get('/asesorias/detalle/{id}', [AsesoriasController::class, 'estudianteDetalleAsesoriaGet'])->name('asesorias.detalle.get');
});

// Asesor
Route::middleware(['auth', 'can:ASESOR'])->group(function () {

    // Asignar materias
    Route::get('asesor/mis-materias', [AsesorMateriaController::class, 'misMateriasGet'])->name('misMateriasGet');
    Route::post('asesor/asignar-materia', [AsesorMateriaController::class, 'asignarMateriaPost'])->name('asignarMateriaPost');
    Route::delete('asesor/eliminar-materia/{id}', [AsesorMateriaController::class, 'eliminarMateriaPost'])->name('eliminarMateriaPost');

    // Asesorías Activas (CONFIRMADA, PROCESO) - Página principal
    Route::get('/asesoriasa/activas', [AsesoriasController::class, 'asesoriasActivasGet'])->name('asesoriasa.activas.get');
    
    // Solicitudes (PENDIENTE)
    Route::get('/asesoriasa/solicitudes', [AsesoriasController::class, 'solicitudesPendientesGet'])->name('asesoriasa.solicitudes.get');
    Route::get('/asesoriasa/solicitudes/count', [AsesoriasController::class, 'countPendingSolicitudes'])->name('asesoriasa.solicitudes.count');
    Route::get('/asesoriasa/activas/count', [AsesoriasController::class, 'countActiveAsesorias'])->name('asesoriasa.activas.count');
    
    // Historial (CANCELADA, FINALIZADA)
    Route::get('/asesoriasa/historial', [AsesoriasController::class, 'todasAsesoriasGet'])->name('asesoriasa.historial.get');
    
    // Mis calificaciones
    Route::get('/mis-calificaciones', [CalificacionesController::class, 'misCalificaciones'])->name('asesoriasa.calificaciones');
    
    // Detalle de asesoría
    Route::get('/asesoriasa/detalle/{id}', [AsesoriasController::class, 'detalleAsesoriaGet'])->name('asesoriasa.detalle.get');
    
    // Actualizar estado de asesoría
    Route::post('/asesoriasa/actualizar/{id}', [AsesoriasController::class, 'actualizarEstado'])->name('asesoriasa.actualizar');
    
    // Guardar enlace de reunión de Meet (solo asesores pueden hacerlo)
    Route::post('/asesoriasa/guardar-meet', [AsesoriasController::class, 'guardarEnlaceMeet'])->name('asesoriasa.guardar.meet');
    
    // Redirección por defecto a asesorías activas
    Route::get('/asesoriasa', function() {
        return redirect()->route('asesoriasa.activas.get');
    })->name('asesoriasa.index');
});

