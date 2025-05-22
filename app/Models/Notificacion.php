<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;
    
    protected $table = 'notificacions';
    protected $fillable = [
        'id_usuario',
        'titulo',
        'mensaje',
        'tipo',
        'icono',
        'url',
        'leida'
    ];
    
    /**
     * Obtener el usuario al que pertenece esta notificación.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
