<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genero extends Model
{
    use HasFactory;
    protected $table = 'genero';
    protected $primaryKey = 'id_genero';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $genero;
    protected $descripcion;
    public $timestamps = false;
    
    protected $fillable = ['genero', 'descripcion'];
    
    /**
     * Get the users associated with this gender.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_genero', 'id_genero');
    }
}

