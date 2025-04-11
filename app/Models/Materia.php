<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;
    protected $table='materia';
    protected $primaryKey='id_materia';
    public $incrementing=true;
    protected $keyType='int';
    protected $codigo;
    protected $nombre;
    protected $descripcion;
    protected $fillable=["codigo","nombre", "descripcion"];
    public $timestamps=false;
}
