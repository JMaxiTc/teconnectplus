<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;
use App\Models\Genero;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function perfil()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
        $generos = Genero::all(); // asegúrate de importar el modelo
        return view('perfil', [
            'usuario' => $usuario,
            'generos' => $generos,
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

}