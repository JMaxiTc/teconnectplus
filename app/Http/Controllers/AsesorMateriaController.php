<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use App\Models\Asesor_materia;
use Illuminate\Support\Facades\Auth;

class AsesorMateriaController extends Controller
{
    // Muestra las materias asignadas y las disponibles para asignar
    public function misMateriasGet()
    {
        $asesorId = Auth::id();

        // Obtener las materias asignadas al asesor
        $materiasAsignadas = Asesor_materia::where('fk_id_asesor', $asesorId)
            ->with('materia') // Eager loading para cargar la materia
            ->get();

        // Obtener todas las materias disponibles que aún no están asignadas
        $todasMaterias = Materia::all();

        return view('asesor.mis-materias',[
            'materiasAsignadas' => $materiasAsignadas,
            'todasMaterias' => $todasMaterias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Mis Materias' => url('/asesor/mis-materias')
            ]
        ]);
    }

    // Asigna una nueva materia al asesor
    public function asignarMateriaPost(Request $request)
    {
        // Validar el formulario
        $request->validate([
            'fk_id_materia' => 'required|exists:materia,id_materia',
        ]);

        $asesorId = Auth::id();
        $materiaId = $request->fk_id_materia;

        // Verificar si la materia ya está asignada
        $yaExiste = Asesor_materia::where('fk_id_asesor', $asesorId)
            ->where('fk_id_materia', $materiaId)
            ->exists();

        if (!$yaExiste) {
            // Asignar la nueva materia
            Asesor_materia::create([
                'fk_id_asesor' => $asesorId,
                'fk_id_materia' => $materiaId,
            ]);
        }
        session()->flash('tipo', 'success'); 
        session()->flash('mensaje', '¡Materia asignada correctamente!');
        return redirect()->back();
    }

    // Eliminar una materia asignada
    public function eliminarMateriaPost($id)
    {
        $asesorId = Auth::id();

        // Eliminar la materia asignada
        Asesor_materia::where('id_asesor_materia', $id)
            ->where('fk_id_asesor', $asesorId)
            ->delete();

        session()->flash('tipo', 'error');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Materia eliminada!');
        return redirect()->back()->with('success', 'Materia eliminada correctamente.');
    }
}
