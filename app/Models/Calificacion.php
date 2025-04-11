<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
    use HasFactory;
    protected $table='calificacion';
    protected $primaryKey='id_calificacion';
    public $incrementing=true;
    protected $keyType='int';
    protected $puntuacion;
    protected $comentario;
    protected $fillable=["puntuacion","comentario"];
    public $timestamps=false;
}
