<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asesoria extends Model
{
    use HasFactory;
    protected $table='asesoria';
    protected $primaryKey='id_asesoria';
    public $incrementing=true;
    protected $keyType='int';
    protected $duracion;
    protected $estado;
    protected $fecha;
    protected $tema;
    protected $fk_id_asesor;
    protected $fk_id_estudiante;
    protected $fk_id_materia;
    protected $fk_id_calificacion;
    protected $fk_id_recurso;	
    protected $fillable=["duracion","estado","fecha","tema","fk_id_asesor","fk_id_estudiante","fk_id_materia","fk_id_calificacion","fk_id_recurso"];
    public $timestamps=false;

    public function estudiante()
{
    return $this->belongsTo(Usuario::class, 'fk_id_estudiante', 'id_usuario');
}

public function asesor()
{
    return $this->belongsTo(Usuario::class, 'fk_id_asesor', 'id_usuario');
}

public function materia()
{
    return $this->belongsTo(Materia::class, 'fk_id_materia', 'id_materia');
}

}
