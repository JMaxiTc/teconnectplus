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
    protected $id_usuario;	
    protected $fillable=["comentario","puntuacion","id_usuario"];
    public $timestamps=false;
    
    /**
     * Get the asesor (user) that owns this calificacion.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
    
    /**
     * Get the asesoria related to this calificacion.
     */
    public function asesoria()
    {
        return $this->hasOne(Asesoria::class, 'fk_id_calificacion', 'id_calificacion');
    }
}
