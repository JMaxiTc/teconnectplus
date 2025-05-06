<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;
    
    protected $table='usuario';
    protected $primaryKey='id_usuario';
    public $incrementing=false; // Cambiar a false para indicar que el ID no es autoincremental
    protected $keyType='int';
    protected $fillable=["id_usuario", "nombre","apellido", "correo", "fechaNacimiento", "id_genero", "rol", "semestre", "password"]; // Añadir id_usuario
    public $timestamps=false;
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    
    /**
     * Determine if user is an asesor
     *
     * @return bool
     */
    public function esAsesor()
    {
        return $this->rol === 'ASESOR';
    }
    
    /**
     * Determine if user is a student
     *
     * @return bool
     */
    public function esEstudiante()
    {
        return $this->rol === 'ESTUDIANTE';
    }
    
    /**
     * Get the gender that owns the user.
     */
    public function genero()
    {
        return $this->belongsTo(Genero::class, 'id_genero', 'id_genero');
    }
}
