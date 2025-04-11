<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;
    protected $table='estudiante';
    protected $primaryKey='id_estudiante';
    public $incrementing=true;
    protected $keyType='int';
    protected $carrera;
    protected $institucion;
    protected $progresoAsesoria;
    protected $tipoAprendizaje;
    protected $fk_id_usuario;
    protected $fillable=["carrera","institucion", "progresoAsesoria", "tipoAprendizaje", "fk_id_usuario"];
    public $timestamps=false;
}
