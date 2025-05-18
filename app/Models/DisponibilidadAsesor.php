<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisponibilidadAsesor extends Model
{
    use HasFactory;

    protected $table = 'disponibilidad_asesores';
    protected $primaryKey = 'id_disponibilidad';
    public $timestamps = true;

    protected $fillable = [
        'id_asesor',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
        'estado',
    ];

    /**
     * Relación con el asesor (usuario).
     */
    public function asesor()
    {
        return $this->belongsTo(Usuario::class, 'id_asesor', 'id_usuario');
    }
    
    /**
     * Obtiene las disponibilidades de un asesor para un día específico
     */
    /**
     * Obtiene las disponibilidades de un asesor para un día específico
     * 
     * @param int $asesorId - ID del asesor
     * @param string|int $diaSemana - Día de la semana (nombre o número del 1-7)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getDisponibilidadesPorDia($asesorId, $diaSemana)
    {
        $query = self::where('id_asesor', $asesorId)
            ->where('estado', 'ACTIVO')
            ->orderBy('hora_inicio');
            
        // Verificar si $diaSemana es un número o un nombre
        if (is_numeric($diaSemana)) {
            // Mapear del número (1-7) al nombre del día
            $diasSemana = ['', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
            if (isset($diasSemana[$diaSemana])) {
                $query->where('dia_semana', $diasSemana[$diaSemana]);
            } else {
                // Si el número no es válido, buscar por el número directamente
                $query->where('dia_semana', $diaSemana);
            }
        } else {
            // Si es un nombre, buscar directamente
            $query->where('dia_semana', $diaSemana);
        }
        
        \Illuminate\Support\Facades\Log::info("Consultando disponibilidades para asesor $asesorId en día $diaSemana");
        
        return $query->get();
    }
}
