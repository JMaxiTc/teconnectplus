<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asesor_materia extends Model
{
    use HasFactory;

    protected $table = 'asesor_materia';
    protected $primaryKey = 'id_asesor_materia';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = ["fk_id_asesor", "fk_id_materia"];
    public $timestamps = false;

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'fk_id_materia');
    }
}
