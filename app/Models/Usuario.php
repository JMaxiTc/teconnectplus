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
    protected $fillable=["id_usuario", "nombre","apellido", "carrera","correo", "fechaNacimiento", "id_genero", "rol", "semestre", "password", "fecha_creacion", "tipo_aprendizaje"]; // Se agregó tipo_aprendizaje
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
     * Determine if user is a admin
     *
     * @return bool
     */
    public function esAdmin()
    {
        return $this->rol === 'ADMIN';
    }
    
    /**
     * Get the gender that owns the user.
     */
    public function genero()
    {
        return $this->belongsTo(Genero::class, 'id_genero', 'id_genero');
    }
    
    /**
     * Get all the calificaciones for this user (when is an asesor).
     */
    public function calificaciones()
    {
        return $this->hasMany(Calificacion::class, 'id_usuario', 'id_usuario');
    }

    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'asesor_materia', 'fk_id_asesor', 'fk_id_materia');
    }

    

/**
 * Get pending asesorias for this user (when is an asesor)
 */
public function solicitudesPendientes()
{
    return $this->hasMany(Asesoria::class, 'fk_id_asesor', 'id_usuario')
        ->where('estado', 'PENDIENTE');
}

/**
 * Get pending asesorias for this user (when is a student)
 */
public function solicitudesPendientesEstudiante()
{
    return $this->hasMany(Asesoria::class, 'fk_id_estudiante', 'id_usuario')
        ->where('estado', 'PENDIENTE');
}

/**
 * Get all asesorias for this user (when is an asesor)
 */
public function asesorias()
{
    return $this->hasMany(Asesoria::class, 'fk_id_asesor', 'id_usuario');
}

public function disponibilidades()
{
    return $this->hasMany(DisponibilidadAsesor::class, 'id_asesor', 'id_usuario');
}


}
