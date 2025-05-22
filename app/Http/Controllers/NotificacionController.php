<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionController extends Controller
{
    /**
     * Mostrar todas las notificaciones del usuario autenticado.
     */
    public function index()
    {
        $notificaciones = Notificacion::where('id_usuario', Auth::user()->id_usuario)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($notificaciones);
    }
    
    /**
     * Obtener solo las notificaciones recientes del usuario (últimas 24 horas).
     */
    public function recientes()
    {
        $ultimasHoras = now()->subHours(24);
        
        $notificaciones = Notificacion::where('id_usuario', Auth::user()->id_usuario)
            ->where('created_at', '>=', $ultimasHoras)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($notificaciones);
    }
    
    /**
     * Obtener el conteo de notificaciones no leídas.
     */
    public function conteo()
    {
        $count = Notificacion::where('id_usuario', Auth::user()->id_usuario)
            ->where('leida', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
    
    /**
     * Marcar una notificación como leída.
     */
    public function marcarLeida(Request $request, $id)
    {
        $notificacion = Notificacion::findOrFail($id);
        
        // Verificar que la notificación pertenezca al usuario
        if ($notificacion->id_usuario != Auth::user()->id_usuario) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
        }
        
        $notificacion->leida = true;
        $notificacion->save();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Marcar todas las notificaciones del usuario como leídas.
     */
    public function marcarTodasLeidas()
    {
        Notificacion::where('id_usuario', Auth::user()->id_usuario)
            ->where('leida', false)
            ->update(['leida' => true]);
            
        return response()->json(['success' => true]);
    }
    
    /**
     * Eliminar una notificación.
     */
    public function eliminar($id)
    {
        $notificacion = Notificacion::findOrFail($id);
        
        // Verificar que la notificación pertenezca al usuario
        if ($notificacion->id_usuario != Auth::user()->id_usuario) {
            return response()->json(['error' => 'No tienes permiso para realizar esta acción'], 403);
        }
        
        $notificacion->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Crear una nueva notificación para un usuario específico.
     * Este método es para uso interno por otras partes del sistema.
     */
    public static function crearNotificacion($idUsuario, $titulo, $mensaje, $tipo = 'info', $icono = null, $url = null)
    {
        return Notificacion::create([
            'id_usuario' => $idUsuario,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'tipo' => $tipo,
            'icono' => $icono,
            'url' => $url,
            'leida' => false
        ]);
    }
}
