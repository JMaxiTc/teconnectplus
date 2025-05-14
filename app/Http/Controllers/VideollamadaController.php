<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asesoria;
use Illuminate\Support\Facades\Auth;

class VideollamadaController extends Controller
{
    /**
     * Guarda un enlace de videollamada para una asesoría
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function guardarEnlace(Request $request)
    {
        $request->validate([
            'id_asesoria' => 'required|exists:asesoria,id_asesoria',
            'enlace_meet' => 'required|url|starts_with:https://meet.google.com/',
        ]);

        $asesoria = Asesoria::findOrFail($request->id_asesoria);
        
        // Verificar permisos (solo el asesor o el estudiante pueden actualizar el enlace)
        if ($asesoria->fk_id_asesor != Auth::id() && $asesoria->fk_id_estudiante != Auth::id()) {
            abort(403, 'No tienes permisos para actualizar esta asesoría');
        }

        // Extraer el código de reunión del enlace
        $meetCode = str_replace('https://meet.google.com/', '', $request->enlace_meet);
        
        // Guardar el enlace y el código en la base de datos
        $asesoria->videoconference_url = $request->enlace_meet;
        $asesoria->meet_code = $meetCode;
        $asesoria->save();
        
        session()->flash('tipo', 'success');
        session()->flash('mensaje', '¡Enlace de videollamada guardado correctamente!');
        
        // Determinar a dónde redirigir según el tipo de usuario
        if ($asesoria->fk_id_asesor == Auth::id()) {
            return redirect()->route('asesoriasa.detalle.get', $request->id_asesoria);
        } else {
            return redirect()->route('asesorias.detalle.get', $request->id_asesoria);
        }
    }
}
