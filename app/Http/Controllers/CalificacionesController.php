<?php

namespace App\Http\Controllers;

use App\Models\Asesoria;
use App\Models\Calificacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalificacionesController extends Controller
{
    /**
     * Muestra las calificaciones de un asesor
     *
     * @return \Illuminate\Http\Response
     */    public function misCalificaciones(Request $request)
    {        // Solo asesores pueden acceder a esta vista
        if (!Auth::user()->esAsesor()) {
            abort(403, 'Acceso denegado');
        }

        $usuario = Auth::user();
        // Obtener todas las calificaciones para calcular el promedio general
        $todasCalificaciones = $usuario->calificaciones;
        $promedio = $todasCalificaciones->avg('puntuacion');
        
        // Establecer el número de calificaciones por página
        $porPagina = 10;
        
        // Crear la consulta base
        $query = $usuario->calificaciones();
        
        // Aplicar filtro por puntuación si está presente
        $filtroEstrellas = $request->input('estrellas');
        if ($filtroEstrellas && in_array($filtroEstrellas, ['1', '2', '3', '4', '5'])) {
            $query->where('puntuacion', $filtroEstrellas);
        }
        
        // Ordenar siempre por más recientes
        $query->orderBy('id_calificacion', 'desc');
          // Paginar las calificaciones
        $calificaciones = $query->paginate($porPagina)->withQueryString();
        
        return view('calificaciones.mis-calificaciones', [
            'calificaciones' => $calificaciones,
            'todasCalificaciones' => $todasCalificaciones,
            'promedio' => $promedio,
            'usuario' => $usuario,
            'filtroEstrellas' => $filtroEstrellas,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Mis Calificaciones' => url('/asesoriasa/calificaciones')
            ]
        ]);
    }

    /**
     * Guarda una nueva calificación para un asesor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardarCalificacion(Request $request)
    {
        $request->validate([
            'id_asesoria' => 'required|exists:asesoria,id_asesoria',
            'puntuacion' => 'required|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:255',
        ]);

        // Buscar la asesoría
        $asesoria = Asesoria::findOrFail($request->id_asesoria);
        
        // Verificar permisos (solo el estudiante de la asesoría puede calificar)
        if ($asesoria->fk_id_estudiante != Auth::id()) {
            abort(403, 'No tienes permisos para calificar esta asesoría');
        }
        
        // Verificar que la asesoría esté finalizada
        if ($asesoria->estado !== 'FINALIZADA') {
            abort(403, 'No puedes calificar una asesoría que no ha finalizado');
        }
        
        // Verificar que no haya una calificación previa para esta asesoría
        if ($asesoria->fk_id_calificacion !== null) {
            abort(403, 'Esta asesoría ya ha sido calificada');
        }
          // Crear la calificación
        $calificacion = new Calificacion();
        $calificacion->puntuacion = $request->puntuacion;
        $calificacion->comentario = $request->comentario ?? ''; // Set empty string if comentario is null
        $calificacion->id_usuario = $asesoria->fk_id_asesor; // Asociar la calificación con el asesor
        $calificacion->save();
        
        // Actualizar la asesoría con la referencia a la calificación
        $asesoria->fk_id_calificacion = $calificacion->id_calificacion;
        $asesoria->save();
        
        session()->flash('tipo', 'success');
        session()->flash('mensaje', '¡Gracias por calificar a tu asesor!');
        
        return redirect()->route('asesorias.detalle.get', $request->id_asesoria);
    }
}
