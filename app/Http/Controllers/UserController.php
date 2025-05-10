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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo' => 'required|email',
            'fechaNacimiento' => 'required|date',
            'semestre' => 'required|integer|min:1|max:12',
            'id_genero' => 'required|exists:genero,id_genero',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $usuario = Usuario::findOrFail($id_usuario);
        $usuario->nombre = strtoupper($request->nombre);
        $usuario->apellido = strtoupper($request->apellido);
        $usuario->correo = $request->correo;
        $usuario->fechaNacimiento = $request->fechaNacimiento;
        $usuario->semestre = $request->semestre;
        $usuario->id_genero = $request->id_genero;
        
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect('perfil')->with('success', 'Datos actualizados correctamente.');
    }

}