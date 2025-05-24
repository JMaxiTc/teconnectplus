<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Asesoria;
use App\Models\Materia;
use App\Models\Recurso;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    
    // Obtener y mostrar todos los usuarios
    public function usuariosGet()
    {
        $usuarios = Usuario::all(); 
        return view('admin.usuariosGet', [
            'usuarios' => $usuarios,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Usuarios' => url('/admin/usuarios')
            ]
        ]);
    }

    // Mostrar formulario para crear un nuevo usuario
    public function usuariosAgregarGet()
    {
        return view('admin.usuariosAgregarGet', [
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Usuarios" => url("/admin/usuarios"),
                "Agregar" => url("/admin/usuarios/agregar")
            ]
        ]);
    }

    // Guardar un nuevo usuario
    public function usuariosAgregarPost(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fechaNacimiento' => 'required|date',
            'id_genero' => 'required|in:1,2,3,4',
            'rol' => 'required|in:ADMIN,ASESOR,ESTUDIANTE',
            'semestre' => 'required|integer|min:1|max:12',
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $maxId = Usuario::max('id_usuario') ?? 0;
        $newId = $maxId + 1;
    
        Usuario::create([
            'id_usuario' => $newId,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'id_genero' => $request->id_genero,
            'rol' => $request->rol,
            'semestre' => $request->semestre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'fecha_creacion' => now(),
        ]);
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Usuario agregado correctamente!');
        return redirect('/admin/usuarios');
    }

    // Mostrar formulario para editar un usuario
    // Mostrar el formulario de edición
    public function usuariosActualizarGet($id_usuario)
    {
        $user = Usuario::findOrFail($id_usuario);
        return view('admin.usuariosActualizarGet', [
            'user' => $user,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Usuarios' => url('/admin/usuarios'),
                'Actualizar' => url("/admin/usuarios/{$id_usuario}/actualizar")
            ]
        ]);
    }

    // Actualizar los datos del usuario
    public function usuariosActualizarPost(Request $request, $id_usuario)
    {
        // Obtener al usuario por ID
        $user = Usuario::findOrFail($id_usuario);

        // Validación de los datos del formulario
        $request->validate([
            'correo' => 'required|email|unique:usuario,correo,' . $user->id_usuario . ',id_usuario',
            'password' => 'nullable|string|min:8|confirmed',
            'estado' => 'required|in:activo,inactivo',
        ]);

        // Solo obtener los campos que se deben actualizar
        $datos = $request->only([
            'correo',
            'estado'
        ]);

        // Si se proporciona una nueva contraseña, agregarla al array de datos
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        // Actualizar el usuario con los datos nuevos
        $user->update($datos);

        // Redirigir con mensaje de éxito
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Usuario actualizado correctamente!');
        return redirect('/admin/usuarios');
    }

    /**
     * Cambiar el estado del usuario (activo/inactivo)
     */
    public function toggleEstadoUsuario($id_usuario)
    {
        // Obtener al usuario por ID
        $user = Usuario::findOrFail($id_usuario);

        // Cambiar el estado (si es activo -> inactivo, si es inactivo -> activo)
        $nuevoEstado = strtolower($user->estado) === 'activo' ? 'inactivo' : 'activo';
        
        // Si estamos desactivando al usuario, forzamos el cierre de todas sus sesiones activas
        if ($nuevoEstado === 'inactivo') {
            // 1. Eliminamos todas las sesiones de la base de datos
            DB::table('sessions')
                ->where('user_id', $id_usuario)
                ->delete();
                
            // 2. Establecemos el estado a inactivo y guardamos la fecha de desactivación
            $user->update([
                'estado' => $nuevoEstado,
                // Podríamos guardar información adicional aquí si fuera necesario
                // 'fecha_desactivacion' => now(),
            ]);
            
            // 3. Creamos una notificación para el usuario (opcional)
            // Esto permitiría ver un mensaje en el sistema de notificaciones
            try {
                if (class_exists('App\\Models\\Notificacion')) {
                    \App\Models\Notificacion::create([
                        'id_usuario' => $id_usuario,
                        'titulo' => 'Cuenta desactivada',
                        'mensaje' => 'Tu cuenta ha sido desactivada por un administrador. Por favor contacta con soporte para más información.',
                        'tipo' => 'warning',
                        'icono' => 'fa-exclamation-circle',
                        'leida' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                // Ignoramos errores de notificaciones, no son críticos
            }
        } else {
            // Si lo estamos activando, simplemente actualizamos su estado
            $user->update(['estado' => $nuevoEstado]);
        }

        // Mensaje informativo
        $accion = $nuevoEstado === 'activo' ? 'activado' : 'desactivado';
        
        // Redirigir con mensaje de éxito
        session()->flash('tipo', 'success');
        session()->flash('mensaje', "¡Usuario {$accion} correctamente! " . 
            ($nuevoEstado === 'inactivo' ? 'Sus sesiones activas serán cerradas en la próxima solicitud.' : ''));
        return redirect('/admin/usuarios');
    }

    public function contenido()
    {
        // Ejemplo: Obtener lista de materiales
        $materiales = Recurso::all();

        return view('admin.contenido', [
            'materiales' => $materiales
        ]);
    }

    public function notificaciones()
    {
        // Ejemplo: Mostrar formulario para enviar notificaciones
        return view('admin.notificaciones');
    }

    public function enviarNotificacion(Request $request)
    {
        // Ejemplo: Lógica para enviar notificaciones
        $request->validate([
            'titulo' => 'required|string|max:255',
            'mensaje' => 'required|string'
        ]);

        // Aquí se enviaría la notificación (ejemplo simplificado)
        // Notificacion::create([...]);

        return redirect()->route('admin.notificaciones')->with('success', 'Notificación enviada correctamente.');
    }

    /**
     * Mostrar la página principal de reportes con estadísticas generales
     */
    public function reportesIndex()
    {
        // Estadísticas generales del sistema
        $totalUsuarios = Usuario::count();
        $totalAsesorias = Asesoria::count();
        $totalMaterias = Materia::count();
        
        $totalEstudiantes = Usuario::where('rol', 'ESTUDIANTE')->count();
        $totalAsesores = Usuario::where('rol', 'ASESOR')->count();
        $totalAdmins = Usuario::where('rol', 'ADMIN')->count();
        
        $asesoriasFinalizadas = Asesoria::where('estado', 'FINALIZADA')->count();
        $asesoriasPendientes = Asesoria::where('estado', 'PENDIENTE')->count();
        $asesoriasActivas = Asesoria::whereIn('estado', ['CONFIRMADA', 'PROCESO'])->count();
        $asesoriasCanceladas = Asesoria::where('estado', 'CANCELADA')->count();
        
        // Obtener datos reales por mes para gráficos
        $asesoriasPorMes = [];
        $usuariosPorMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $asesoriasPorMes[] = Asesoria::whereMonth('fecha', $i)->count();
            $usuariosPorMes[] = Usuario::whereMonth('fecha_creacion', $i)->count();
        }
        
        // Estadísticas de usuarios con asesorías
        $asesoriasPorUsuario = Usuario::join('asesoria', 'usuario.id_usuario', '=', 'asesoria.fk_id_asesor')
            ->leftJoin('calificacion', 'asesoria.fk_id_calificacion', '=', 'calificacion.id_calificacion')
            ->select(
                'usuario.nombre',
                'usuario.rol as tipo',
                \DB::raw('count(asesoria.id_asesoria) as total_asesorias'),
                \DB::raw('COALESCE(AVG(calificacion.puntuacion), 0) as promedio_calificacion')
            )
            ->groupBy('usuario.id_usuario', 'usuario.nombre', 'usuario.rol')
            ->orderBy('total_asesorias', 'desc')
            ->limit(10)
            ->get();
        
        // Estadísticas de materias con asesorías
        $asesoriasPorMateria = Materia::join('asesoria', 'materia.id_materia', '=', 'asesoria.fk_id_materia')
            ->leftJoin('calificacion', 'asesoria.fk_id_calificacion', '=', 'calificacion.id_calificacion')
            ->select(
                'materia.nombre',
                \DB::raw('count(asesoria.id_asesoria) as total_asesorias'),
                \DB::raw('COALESCE(AVG(calificacion.puntuacion), 0) as promedio_calificacion')
            )
            ->groupBy('materia.id_materia', 'materia.nombre')
            ->orderBy('total_asesorias', 'desc')
            ->limit(10)
            ->get();
        
        // Meses para gráficos
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        
        return view('admin.reportes.index', [
            'totalUsuarios' => $totalUsuarios,
            'totalAsesorias' => $totalAsesorias,
            'totalMaterias' => $totalMaterias,
            'totalEstudiantes' => $totalEstudiantes,
            'totalAsesores' => $totalAsesores,
            'totalAdmins' => $totalAdmins,
            'asesoriasFinalizadas' => $asesoriasFinalizadas,
            'asesoriasPendientes' => $asesoriasPendientes,
            'asesoriasActivas' => $asesoriasActivas,
            'asesoriasCanceladas' => $asesoriasCanceladas,
            'meses' => $meses,
            'asesoriasPorMes' => $asesoriasPorMes,
            'usuariosPorMes' => $usuariosPorMes,
            'asesoriasPorUsuario' => $asesoriasPorUsuario,
            'asesoriasPorMateria' => $asesoriasPorMateria,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Reportes' => url('/admin/reportes')
            ]
        ]);
    }

    /**
     * Mostrar reporte detallado de usuarios
     */
    public function reporteUsuarios()
    {
        // Estadísticas de usuarios
        $usuarios = Usuario::all();
        $estudiantesPorSemestre = Usuario::where('rol', 'ESTUDIANTE')
            ->select('semestre', \DB::raw('count(*) as total'))
            ->groupBy('semestre')
            ->orderBy('semestre')
            ->get();
        
        // Para gráfico de crecimiento mensual (datos reales basados en fecha_creacion)
        $usuariosPorMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $usuariosPorMes[] = Usuario::whereMonth('fecha_creacion', $i)->count();
        }
        
        return view('admin.reportes.usuarios', [
            'usuarios' => $usuarios,
            'estudiantesPorSemestre' => $estudiantesPorSemestre,
            'usuariosPorMes' => $usuariosPorMes,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Reportes' => url('/admin/reportes'),
                'Usuarios' => url('/admin/reportes/usuarios')
            ]
        ]);
    }

    /**
     * Mostrar reporte detallado de asesorías
     */
    public function reporteAsesorias()
    {
        // Estadísticas de asesorías
        $asesorias = Asesoria::with(['estudiante', 'asesor', 'materia'])->get();
        
        // Agrupamiento por estado
        $asesoriasPorEstado = Asesoria::select('estado', \DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();
        
        // Agrupamiento por mes (datos reales)
        $asesoriasPorMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $asesoriasPorMes[] = Asesoria::whereMonth('fecha', $i)->count();
        }
        
        // Agrupamiento por materias más solicitadas
        $materiasMasSolicitadas = Asesoria::join('materia', 'asesoria.fk_id_materia', '=', 'materia.id_materia')
            ->select('materia.nombre', \DB::raw('count(*) as total'))
            ->groupBy('materia.nombre')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
        
        return view('admin.reportes.asesorias', [
            'asesorias' => $asesorias,
            'asesoriasPorEstado' => $asesoriasPorEstado,
            'asesoriasPorMes' => $asesoriasPorMes,
            'materiasMasSolicitadas' => $materiasMasSolicitadas,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Reportes' => url('/admin/reportes'),
                'Asesorías' => url('/admin/reportes/asesorias')
            ]
        ]);
    }

    /**
     * Mostrar reporte detallado de materias
     */
    public function reporteMaterias()
    {
        // Estadísticas de materias
        $materias = Materia::all();
        
        // Materias con más asesorías
        $materiasConMasAsesorias = Materia::join('asesoria', 'materia.id_materia', '=', 'asesoria.fk_id_materia')
            ->select('materia.nombre', \DB::raw('count(*) as total_asesorias'))
            ->groupBy('materia.nombre')
            ->orderBy('total_asesorias', 'desc')
            ->get();
        
        // Materias con más asesores asignados
        $materiasConMasAsesores = Materia::join('asesor_materia', 'materia.id_materia', '=', 'asesor_materia.fk_id_materia')
            ->select('materia.nombre', \DB::raw('count(*) as total_asesores'))
            ->groupBy('materia.nombre')
            ->orderBy('total_asesores', 'desc')
            ->get();
        
        return view('admin.reportes.materias', [
            'materias' => $materias,
            'materiasConMasAsesorias' => $materiasConMasAsesorias,
            'materiasConMasAsesores' => $materiasConMasAsesores,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Reportes' => url('/admin/reportes'),
                'Materias' => url('/admin/reportes/materias')
            ]
        ]);
    }

    /**
     * Generar PDF de reportes
     */
    public function generarPDF($tipo)
    {
        switch ($tipo) {
            case 'usuarios':
                // Datos para el reporte de usuarios
                $estudiantesPorSemestre = Usuario::where('rol', 'ESTUDIANTE')
                    ->select('semestre', \DB::raw('count(*) as total'))
                    ->groupBy('semestre')
                    ->orderBy('semestre')
                    ->get();
                
                $usuariosPorMes = [];
                for ($i = 1; $i <= 12; $i++) {
                    $usuariosPorMes[] = Usuario::whereMonth('fecha_creacion', $i)->count();
                }
                
                $data = [
                    'titulo' => 'Reporte de Usuarios',
                    'fecha' => date('Y-m-d'),
                    'usuarios' => Usuario::all(),
                    'totalUsuarios' => Usuario::count(),
                    'totalEstudiantes' => Usuario::where('rol', 'ESTUDIANTE')->count(),
                    'totalAsesores' => Usuario::where('rol', 'ASESOR')->count(),
                    'estudiantesPorSemestre' => $estudiantesPorSemestre,
                    'usuariosPorMes' => $usuariosPorMes,
                    'meses' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                ];
                $view = 'admin.reportes.pdf.usuarios';
                break;
                
            case 'asesorias':
                // Datos para el reporte de asesorías
                $asesoriasPorMes = [];
                for ($i = 1; $i <= 12; $i++) {
                    $asesoriasPorMes[] = Asesoria::whereMonth('fecha', $i)->count();
                }
                
                $materiasMasSolicitadas = Asesoria::join('materia', 'asesoria.fk_id_materia', '=', 'materia.id_materia')
                    ->select('materia.nombre', \DB::raw('count(*) as total'))
                    ->groupBy('materia.nombre')
                    ->orderBy('total', 'desc')
                    ->take(10)
                    ->get();
                
                $data = [
                    'titulo' => 'Reporte de Asesorías',
                    'fecha' => date('Y-m-d'),
                    'asesorias' => Asesoria::with(['estudiante', 'asesor', 'materia'])->get(),
                    'totalAsesorias' => Asesoria::count(),
                    'finalizadas' => Asesoria::where('estado', 'FINALIZADA')->count(),
                    'pendientes' => Asesoria::where('estado', 'PENDIENTE')->count(),
                    'asesoriasPorMes' => $asesoriasPorMes,
                    'materiasMasSolicitadas' => $materiasMasSolicitadas,
                    'meses' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
                ];
                $view = 'admin.reportes.pdf.asesorias';
                break;
                
            case 'materias':
                // Datos para el reporte de materias
                $materiasConMasAsesorias = Materia::join('asesoria', 'materia.id_materia', '=', 'asesoria.fk_id_materia')
                    ->select('materia.nombre', \DB::raw('count(*) as total_asesorias'))
                    ->groupBy('materia.nombre')
                    ->orderBy('total_asesorias', 'desc')
                    ->get();
                
                $materiasConMasAsesores = Materia::join('asesor_materia', 'materia.id_materia', '=', 'asesor_materia.fk_id_materia')
                    ->select('materia.nombre', \DB::raw('count(*) as total_asesores'))
                    ->groupBy('materia.nombre')
                    ->orderBy('total_asesores', 'desc')
                    ->get();
                
                $data = [
                    'titulo' => 'Reporte de Materias',
                    'fecha' => date('Y-m-d'),
                    'materias' => Materia::all(),
                    'totalMaterias' => Materia::count(),
                    'materiasConMasAsesorias' => $materiasConMasAsesorias,
                    'materiasConMasAsesores' => $materiasConMasAsesores
                ];
                $view = 'admin.reportes.pdf.materias';
                break;
                
            default:
                return redirect()->back()->with('error', 'Tipo de reporte no válido');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data);
        return $pdf->download("reporte-$tipo-" . date('Y-m-d') . ".pdf");
    }
}
