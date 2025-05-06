<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }
        
        // Obtener la URL de redirección (si existe)
        $redirect = $request->query('redirect');
        
        return view('auth.login', compact('redirect'));
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'correo' => $credentials['correo'],
            'password' => $credentials['password'],
        ])) {
            $request->session()->regenerate();
            
            // Redireccionar a la URL especificada o a la página principal
            $redirect = $request->input('redirect');
            return redirect()->intended($redirect ?: '/');
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->except('password'));
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegistrationForm(Request $request)
    {
        if (Auth::check()) {
            return redirect('/');
        }
        
        $generos = Genero::all();
        $selectedRole = $request->query('rol');
        
        return view('auth.register', compact('generos', 'selectedRole'));
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'correo' => 'required|string|email|max:80|unique:usuario',
            'password' => 'required|string|min:8|confirmed',
            'fechaNacimiento' => 'required|date',
            'id_genero' => 'nullable|exists:genero,id_genero',
            'rol' => ['required', Rule::in(['ESTUDIANTE', 'ASESOR'])],
            'semestre' => 'required|integer|min:1|max:12',
        ]);

        // Buscar el máximo ID de usuario y añadir 1 para el nuevo usuario
        $maxId = Usuario::max('id_usuario') ?? 0;
        $newId = $maxId + 1;

        $usuario = Usuario::create([
            'id_usuario' => $newId,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'fechaNacimiento' => $request->fechaNacimiento,
            'id_genero' => $request->id_genero,
            'rol' => $request->rol,
            'semestre' => $request->semestre,
        ]);

        Auth::login($usuario);
        
        return redirect('/');
    }
}
