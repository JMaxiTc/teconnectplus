<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacionesPruebaController extends Controller
{
    /**
     * Crear notificaciones de prueba para el usuario autenticado.
     */
    public function crearNotificacionesDePrueba()
    {
        $usuario = Auth::user();
        
        // Crear diferentes tipos de notificaciones
        NotificacionController::crearNotificacion(
            $usuario->id_usuario,
            'Asesoría confirmada',
            'El asesor Juan Pérez ha confirmado tu asesoría para Matemáticas.',
            'success',
            'bi-check-circle',
            '/asesorias'
        );
        
        NotificacionController::crearNotificacion(
            $usuario->id_usuario,
            'Asesoría iniciada',
            'Tu asesoría de Programación Web con María González ha comenzado.',
            'info',
            'bi-play-circle',
            '/asesorias'
        );
        
        NotificacionController::crearNotificacion(
            $usuario->id_usuario,
            'Asesoría cancelada',
            'El asesor Carlos Rodríguez ha cancelado tu asesoría para Bases de Datos. Motivo: Problemas de salud.',
            'warning',
            'bi-x-circle',
            '/asesorias'
        );
        
        NotificacionController::crearNotificacion(
            $usuario->id_usuario,
            'Nueva solicitud de asesoría',
            'El estudiante Ana López ha solicitado una asesoría para Física.',
            'info',
            'bi-clipboard-plus',
            '/asesoriasa'
        );
        
        return redirect()->back()->with('tipo', 'success')->with('mensaje', 'Notificaciones de prueba creadas correctamente');
    }
}
