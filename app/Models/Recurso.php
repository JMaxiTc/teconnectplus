<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recurso extends Model
{
    use HasFactory;
    protected $table='recurso';
    protected $primaryKey='id_recurso';
    public $incrementing=true;
    protected $keyType='int';
    protected $nombre;
    protected $tamaño;
    protected $tipo;
    protected $url;
    protected $fechaSubida;	
    protected $fillable=["nombre","tamaño","tipo","url","fechaSubida"];
    public $timestamps=false;
}
