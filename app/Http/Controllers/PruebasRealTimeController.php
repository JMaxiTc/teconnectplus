<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notificacion;
use Illuminate\Support\Facades\Auth;

class PruebasRealTimeController extends Controller
{
    /**
     * Crea una notificación instantánea para el usuario actual (para pruebas)
     */
    public function enviarNotificacionInstantanea(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|in:info,success,error,warning',
            'mensaje' => 'required|string|max:255',
        ]);
        
        $notificacion = Notificacion::create([
            'id_usuario' => Auth::id(),
            'titulo' => $request->input('titulo', 'Notificación de prueba'),
            'mensaje' => $request->mensaje,
            'tipo' => $request->tipo,
            'icono' => $request->input('icono', null),
            'url' => $request->input('url', null),
            'leida' => false
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notificación creada',
            'notificacion' => $notificacion
        ]);
    }

    /**
     * Vista para probar notificaciones en tiempo real
     */
    public function vistaTest()
    {
        return view('pruebas.notificaciones-test');
    }
}
