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
}
