<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor extends Model
{
    use HasFactory;
    protected $table='asesor';
    protected $primaryKey='id_asesor';
    public $incrementing=true;
    protected $keyType='int';
    protected $carrera;
    protected $especialidad;
    protected $perfilDescripcion;
    protected $calificacionPromedio;
    protected $fk_id_usuario;
    protected $fillable=["carrera","especialidad", "perfilDescripcion", "calificacionPromedio", "fk_id_usuario"];
    public $timestamps=false;
}
