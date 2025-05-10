<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    
    // Obtener y mostrar todos los usuarios
    public function usuariosGet()
    {
        $usuarios = Usuario::all(); 
        return view('admin.usuariosGet', [
            'usuarios' => $usuarios,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Usuarios' => url('/admin/usuarios')
            ]
        ]);
    }

    // Mostrar formulario para crear un nuevo usuario
    public function usuariosAgregarGet()
    {
        return view('admin.usuariosAgregarGet', [
            "breadcrumbs" => [
                "Inicio" => url("/"),
                "Usuarios" => url("/admin/usuarios"),
                "Agregar" => url("/admin/usuarios/agregar")
            ]
        ]);
    }

    // Guardar un nuevo usuario
    public function usuariosAgregarPost(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fechaNacimiento' => 'required|date',
            'id_genero' => 'required|in:1,2,3,4',
            'rol' => 'required|in:ADMIN,ASESOR,ESTUDIANTE',
            'semestre' => 'required|integer|min:1|max:12',
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $maxId = Usuario::max('id_usuario') ?? 0;
        $newId = $maxId + 1;
    
        Usuario::create([
            'id_usuario' => $newId,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'fechaNacimiento' => $request->fechaNacimiento,
            'id_genero' => $request->id_genero,
            'rol' => $request->rol,
            'semestre' => $request->semestre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
        ]);
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Usuario agregado correctamente!');
        return redirect('/admin/usuarios');
    }

    // Mostrar formulario para editar un usuario
    // Mostrar el formulario de edición
    public function usuariosActualizarGet($id_usuario)
    {
        $user = Usuario::findOrFail($id_usuario);
        return view('admin.usuariosActualizarGet', [
            'user' => $user,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Usuarios' => url('/admin/usuarios'),
                'Actualizar' => url("/admin/usuarios/{$id_usuario}/actualizar")
            ]
        ]);
    }

    // Actualizar los datos del usuario
    public function usuariosActualizarPost(Request $request, $id_usuario)
    {
        // Obtener al usuario por ID
        $user = Usuario::findOrFail($id_usuario);

        // Validación de los datos del formulario
        $request->validate([
            'correo' => 'required|email|unique:usuario,correo,' . $user->id_usuario . ',id_usuario',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Solo obtener los campos que se deben actualizar
        $datos = $request->only([
            'correo'
        ]);

        // Si se proporciona una nueva contraseña, agregarla al array de datos
        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        // Actualizar el usuario con los datos nuevos
        $user->update($datos);

        // Redirigir con mensaje de éxito
        session()->flash('tipo', 'success');  // Tipo de mensaje: 'success', 'error', etc.
        session()->flash('mensaje', '¡Usuario actualizado correctamente!');
        return redirect('/admin/usuarios');
    }

}
