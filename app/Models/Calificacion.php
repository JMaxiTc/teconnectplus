<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calificacion extends Model
{
    use HasFactory;
    protected $table='calificacion';
    protected $primaryKey='id_calificacion';
    public $incrementing=true;
    protected $keyType='int';
    protected $comentario;
    protected $puntuacion;	
    protected $fillable=["comentario","puntuacion"];
    public $timestamps=false;
}
