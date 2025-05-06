<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materia extends Model
{
    use HasFactory;
    protected $table='materia';
    protected $primaryKey='id_materia';
    public $incrementing=true;
    protected $keyType='int';
    protected $descripcion;
    protected $codigo;
    protected $nombre;	
    protected $fillable=["descripcion","codigo","nombre"];
    public $timestamps=false;
}
