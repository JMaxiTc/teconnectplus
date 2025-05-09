<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function perfil()
    {
        $usuario = Auth::user(); // Obtener el usuario autenticado
        return view('perfil', [
            'usuario' => $usuario,
            'breadcrumbs' => [
                'Inicio' => url('/'),
                'Mi Perfil' => url('/perfil')
            ]
        ]);
    }

}