<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Genero;
use App\Models\DisponibilidadAsesor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function perfil()
{
    $usuario = Auth::user();
    
    // Cargar solamente las disponibilidades del usuario autenticado
    $usuario->load(['disponibilidades' => function($query) {
        $query->where('id_asesor', Auth::id());
    }, 'genero']);

    return view('perfil', [
        'usuario' => $usuario,
        'generos' => Genero::all(),
        'breadcrumbs' => [
            'Inicio' => url('/'),
            'Mi Perfil' => url('/perfil')
        ]
    ]);
}

    public function actualizar(Request $request, $id_usuario)
    {
        // Validar solo los campos que están presentes en la solicitud
        $rules = [];
        
        if ($request->has('nombre')) $rules['nombre'] = 'required|string|max:255';
        if ($request->has('apellido')) $rules['apellido'] = 'required|string|max:255';
        if ($request->has('correo')) $rules['correo'] = 'required|email';
        if ($request->has('fechaNacimiento')) $rules['fechaNacimiento'] = 'required|date';
        if ($request->has('semestre')) $rules['semestre'] = 'required|integer|min:1|max:12';
        if ($request->has('id_genero')) $rules['id_genero'] = 'required|exists:genero,id_genero';
        if ($request->has('password')) $rules['password'] = 'nullable|min:8|confirmed';
        if ($request->has('tipo_aprendizaje')) $rules['tipo_aprendizaje'] = 'nullable|string|max:50';
        
        try {
            // Log para depuración
            \Log::debug('Actualizando perfil de usuario', [
                'id_usuario' => $id_usuario,
                'request_data' => $request->all(),
                'is_ajax' => $request->ajax()
            ]);
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                $errorMessage = $validator->errors()->first();
                $allErrors = $validator->errors()->toArray();
                \Log::warning('Validación fallida', [
                    'errors' => $allErrors
                ]);
                
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false, 
                        'message' => $errorMessage,
                        'errors' => $allErrors,
                        'field' => key($allErrors) // Campo con el primer error
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $usuario = Usuario::findOrFail($id_usuario);
            
            // Actualizar solo los campos que están presentes en la solicitud
            if ($request->has('nombre')) $usuario->nombre = strtoupper($request->nombre);
            if ($request->has('apellido')) $usuario->apellido = strtoupper($request->apellido);
            if ($request->has('correo')) $usuario->correo = $request->correo;
            if ($request->has('fechaNacimiento')) $usuario->fechaNacimiento = $request->fechaNacimiento;
            if ($request->has('semestre')) $usuario->semestre = $request->semestre;
            if ($request->has('id_genero')) $usuario->id_genero = $request->id_genero;
            if ($request->has('tipo_aprendizaje')) $usuario->tipo_aprendizaje = $request->tipo_aprendizaje;
            
            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }
            
            $usuario->save();
            
            // Recargar relaciones para la respuesta JSON
            $usuario->load('genero');
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Datos actualizados correctamente.',
                    'usuario' => $usuario
                ]);
            }
            
            return redirect('perfil')->with('success', 'Datos actualizados correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error actualizando perfil', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error al actualizar: ' . $e->getMessage(),
                    'error_type' => get_class($e)
                ], 500);
            }
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function guardarDisponibilidad(Request $request)
{
    $request->validate([
        'dias' => 'required|array',
        'hora_inicio' => 'required|array',
        'hora_fin' => 'required|array',
    ]);

    $idAsesor = Auth::id();
    $conflictos = [];

    for ($i = 0; $i < count($request->dias); $i++) {
        $dia = $request->dias[$i];
        $inicio = $request->hora_inicio[$i];
        $fin = $request->hora_fin[$i];

        if ($inicio >= $fin) {
            $conflictos[] = "$dia $inicio - $fin (Hora fin debe ser mayor)";
            continue;
        }

        // Verificar traslape con horarios existentes
        $existeTraslape = \App\Models\DisponibilidadAsesor::where('id_asesor', $idAsesor)
            ->where('dia_semana', $dia)
            ->where(function ($q) use ($inicio, $fin) {
                $q->whereBetween('hora_inicio', [$inicio, $fin])
                  ->orWhereBetween('hora_fin', [$inicio, $fin])
                  ->orWhere(function ($query) use ($inicio, $fin) {
                      $query->where('hora_inicio', '<=', $inicio)
                            ->where('hora_fin', '>=', $fin);
                  });
            })
            ->exists();

        if ($existeTraslape) {
            $conflictos[] = "$dia $inicio - $fin (conflicto con horario existente)";
            continue;
        }

        \App\Models\DisponibilidadAsesor::create([
            'id_asesor' => $idAsesor,
            'dia_semana' => $dia,
            'hora_inicio' => $inicio,
            'hora_fin' => $fin,
            'estado' => 'ACTIVO'
        ]);
    }

    if (count($conflictos)) {
        return response()->json([
            'success' => false,
            'message' => 'Se detectaron conflictos con los siguientes horarios:',
            'conflictos' => $conflictos
        ], 409);
    }

    return response()->json(['success' => true]);
}


public function cambiarEstadoDisponibilidad($id)
{
    $disp = DisponibilidadAsesor::findOrFail($id);
    
    // Verificar que el horario pertenezca al asesor autenticado
    if ($disp->id_asesor != Auth::id()) {
        return response()->json(['success' => false, 'message' => 'No tienes permiso para modificar este horario'], 403);
    }
    
    $disp->estado = $disp->estado === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';
    $disp->save();
    return response()->json(['success' => true]);
}

public function actualizarDisponibilidad(Request $request, $id)
{
    $request->validate([
        'dia_semana' => 'required',
        'hora_inicio' => 'required',
        'hora_fin' => 'required',
    ]);

    $disp = DisponibilidadAsesor::findOrFail($id);
    
    // Verificar que el horario pertenezca al asesor autenticado
    if ($disp->id_asesor != Auth::id()) {
        return response()->json(['success' => false, 'message' => 'No tienes permiso para modificar este horario'], 403);
    }

    // Validar rango lógico
    if ($request->hora_inicio >= $request->hora_fin) {
        return response()->json([
            'success' => false,
            'message' => 'La hora de fin debe ser mayor que la hora de inicio.'
        ], 422);
    }

    // Verificar traslapes con otros horarios del mismo asesor
    $traslape = DisponibilidadAsesor::where('id_asesor', $disp->id_asesor)
        ->where('id_disponibilidad', '!=', $id) // excluir el actual
        ->where('dia_semana', $request->dia_semana)
        ->where(function ($q) use ($request) {
            $q->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
              ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
              ->orWhere(function ($query) use ($request) {
                  $query->where('hora_inicio', '<=', $request->hora_inicio)
                        ->where('hora_fin', '>=', $request->hora_fin);
              });
        })
        ->exists();

    if ($traslape) {
        return response()->json([
            'success' => false,
            'message' => 'El nuevo horario se superpone con otro ya registrado para el mismo día.'
        ], 409);
    }

    $disp->update([
        'dia_semana' => $request->dia_semana,
        'hora_inicio' => $request->hora_inicio,
        'hora_fin' => $request->hora_fin,
    ]);

    return response()->json(['success' => true]);
}



}