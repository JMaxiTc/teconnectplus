<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materia;
use Illuminate\View\View;
use App\Helpers\MateriaHelper;
use App\Models\Recurso;

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
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Materia agregada correctamente!');
        return redirect('/catalogos/materias');
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
        
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Materia actualizada correctamente!');
        return redirect('/catalogos/materias');
    }

    public function materialesGet($idMateria)
{
    $materia = Materia::findOrFail($idMateria); // Obtener la materia por ID
    $materiales = Recurso::where('fk_id_materia', $idMateria)->get(); // Obtener los materiales relacionados

    return view('catalogos.materialesGet', [ // Cambiar a la vista correcta
        "materia" => $materia,
        "materiales" => $materiales,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Materias" => url("/catalogos/materias"),
            "Materiales" => url("/catalogos/materias/{$idMateria}/materiales")
        ]
    ]);
    }

    public function guardar(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'tipo' => 'required|string|in:documento,video,enlace',
        'archivo' => 'nullable|file|max:10240', // 10MB
        'url' => 'nullable|url',
        'id_materia' => 'required|exists:materia,id_materia',
    ]);

    $recurso = new Recurso();
    $recurso->nombre = $request->nombre;
    $recurso->tipo = $request->tipo;
    $recurso->fechaSubida = now();

    if ($request->tipo === 'enlace') {
        $recurso->url = $request->url;
        $recurso->tamaño = 0;
    } else {
        $archivo = $request->file('archivo');
        $ruta = $archivo->store('materiales', 'public');
        $recurso->url = 'storage/' . $ruta;
        $recurso->tamaño = $archivo->getSize();
    }

    $recurso->fk_id_materia = $request->id_materia;
    $recurso->save();

    session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
    session()->flash('mensaje', '¡Material agregado correctamente!');
    return redirect()->back();
}

public function materialesTodosGet(Request $request)
{
    $query = Recurso::with('materia');

    if ($request->filled('materia')) {
        $query->where('fk_id_materia', $request->materia);
    }

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('busqueda')) {
        $query->where('nombre', 'like', '%' . $request->busqueda . '%');
    }

    // Ordenar por 'fechaSubida' o cualquier otra columna que prefieras
    $materiales = $query->orderBy('fechaSubida', 'desc')->paginate(9);

    // Obtener las materias para los filtros
    $materias = Materia::orderBy('nombre')->get();

    return view('catalogos.materialesAll', [ // Cambiar a la vista correcta
        "materias" => $materias,
        "materiales" => $materiales,
        "query" => $query,
        "breadcrumbs" => [
            "Inicio" => url("/"),
            "Materiales" => url("/catalogos/materiales")
        ]
    ]);
}





}
