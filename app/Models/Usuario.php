<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table='usuario';
    protected $primaryKey='id_usuario';
    public $incrementing=true;
    protected $keyType='int';
    protected $nombre;
    protected $apellido;
    protected $correo;
    protected $fechaNacimiento;
    protected $genero;
    protected $rol;
    protected $semestre;
    protected $fillable=["nombre","apellido", "correo", "fechaNacimiento", "genero", "rol", "semestre"];
    public $timestamps=false;
}
