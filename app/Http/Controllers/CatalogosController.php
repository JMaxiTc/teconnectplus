<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use Illuminate\View\View;
use App\Helpers\MateriaHelper;

class CatalogosController extends Controller
{

    public function index()
    {
        $materias = Materia::all(); // o paginadas si deseas
        return view('home', [
            'materias' => $materias,
            'getIconForMateria' => [MateriaHelper::class, 'getIconForMateria'],
            'breadcrumbs' => [
                'Inicio' => url('/'),
            ]
        ]);
    }

    public function materiasGet(): View
    {
        $materias = Materia::all();
        return view('catalogos.materiasGet', [
            'materias' => $materias,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Materias' => url('/catalogos/materias')
            ]
        ]);
    }

    public function materiasAgregarGet(): View
    {
        return view('catalogos.materiasAgregarGet', [
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Materias" => url("/catalogos/materias"),
                "Agregar" => url("/catalogos/materias/agregar")
            ]
        ]);
    }

    public function materiasAgregarPost(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:80',
        ]);

        Materia::create([
            'codigo' => strtoupper($request->codigo),
            'nombre' => strtoupper($request->nombre),
            'descripcion' => ucfirst($request->descripcion),
        ]);

        return redirect('/catalogos/materias')->with('success', 'Materia agregada correctamente.');
    }

    public function materiasActualizarGet($id)
    {
        $materia = Materia::findOrFail($id);
        return view('catalogos.materiasActualizarGet', [
            'materia' => $materia,
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Materias" => url("/catalogos/materias"),
                "Actualizar" => url("/catalogos/materias/{$id}/actualizar")
            ]
        ]);
    }

    public function materiasActualizarPost(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:100',
            'descripcion' => 'required|string|max:80'
        ]);

        $materia = Materia::findOrFail($id);
        $materia->codigo = strtoupper($request->codigo);
        $materia->nombre = strtoupper($request->nombre);
        $materia->descripcion = ucfirst($request->descripcion);
        $materia->save();

        return redirect('/catalogos/materias')->with('success', 'Materia actualizada correctamente.');
    }
}
