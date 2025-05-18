<?php

namespace App\Http\Controllers;

use App\Models\DisponibilidadAsesor;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    /**
     * Crea una disponibilidad de prueba para el asesor autenticado
     */
    public function crearDisponibilidadPrueba()
    {
        // Verificar que el usuario es un asesor
        if (Auth::user()->rol !== 'ASESOR') {
            return response()->json(['error' => 'Solo los asesores pueden crear disponibilidades'], 403);
        }
        
        // Crear disponibilidades para todos los días de la semana (Lunes a Viernes)
        $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $disponibilidadesCreadas = [];
        
        foreach ($diasSemana as $dia) {
            // Crear disponibilidad de 9am a 1pm
            $disponibilidad1 = DisponibilidadAsesor::create([
                'id_asesor' => Auth::id(),
                'dia_semana' => $dia,
                'hora_inicio' => '09:00:00',
                'hora_fin' => '13:00:00',
                'estado' => 'ACTIVO',
            ]);
            
            // Crear disponibilidad de 3pm a 7pm
            $disponibilidad2 = DisponibilidadAsesor::create([
                'id_asesor' => Auth::id(),
                'dia_semana' => $dia,
                'hora_inicio' => '15:00:00',
                'hora_fin' => '19:00:00',
                'estado' => 'ACTIVO',
            ]);
            
            $disponibilidadesCreadas[] = $disponibilidad1;
            $disponibilidadesCreadas[] = $disponibilidad2;
        }
        
        return response()->json([
            'message' => 'Disponibilidades de prueba creadas correctamente',
            'disponibilidades' => $disponibilidadesCreadas
        ]);
    }
}
