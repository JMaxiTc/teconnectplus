<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recurso extends Model
{
    use HasFactory;

    protected $table = 'recurso';
    protected $primaryKey = 'id_recurso';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // Lista de atributos que pueden ser asignados en masa
    protected $fillable = [
        'nombre',
        'tamaño',
        'tipo',
        'url',
        'fechaSubida',
        'fk_id_materia' // <- Asegúrate de incluir este campo
    ];

    // Opcional: relación con Materia
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'fk_id_materia');
    }
}
