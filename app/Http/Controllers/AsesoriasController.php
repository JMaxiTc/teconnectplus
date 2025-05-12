<?php

namespace App\Http\Controllers;

use App\Models\Asesoria;
use App\Models\Usuario;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsesoriasController extends Controller
{
   public function estudianteSolicitarGet()
    {
        $materias = Materia::all();
        return view('asesorias.solicitar', compact('materias'));
    }

    public function asesoresPorMateria($id)
    {
        $asesores = Usuario::whereHas('materias', function ($q) use ($id) {
            $q->where('materia.id_materia', $id);
        })->get();

        return response()->json($asesores);
    }

    public function estudianteSolicitarPost(Request $request)
    {
        $request->validate([
            'materia' => 'required|exists:materia,id_materia',
            'asesor' => 'required|exists:usuario,id_usuario',
            'tema' => 'required|string|max:255',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'duracion' => 'required|in:30,45,60,90',
        ]);

        // Convertimos los minutos a formato TIME (HH:MM:SS)
        $horas = floor($request->duracion / 60);
        $minutos = $request->duracion % 60;
        $duracionFormatted = sprintf('%02d:%02d:00', $horas, $minutos);
        
        Asesoria::create([
            'tema' => $request->tema,
            'fecha' => $request->fecha . ' ' . $request->hora,
            'duracion' => $duracionFormatted,
            'fk_id_materia' => $request->materia,
            'fk_id_asesor' => $request->asesor,
            'fk_id_estudiante' => Auth::user()->id_usuario,
            'estado' => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Asesoría solicitada correctamente');
    }

    public function asesorSolicitudesGet()
{
    $asesorias = Asesoria::with(['estudiante', 'materia'])
        ->where('fk_id_asesor', Auth::user()->id_usuario)
        ->where('estado', 'pendiente')
        ->orderBy('id_asesoria', 'desc')
        ->get();

    return view('asesorias.solicitudes', compact('asesorias'));
}

public function actualizarEstado(Request $request, $id)
{
    $asesoria = Asesoria::findOrFail($id);

    if ($asesoria->fk_id_asesor != Auth::user()->id_usuario) {
        abort(403);
    }

    $request->validate([
        'estado' => 'required|in:aceptada,rechazada',
    ]);

    $asesoria->estado = $request->estado;
    $asesoria->save();

    return redirect()->back()->with('success', 'Estado actualizado correctamente');
}

}


