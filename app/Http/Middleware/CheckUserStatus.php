<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            // Obtenemos el usuario fresco de la base de datos para ver su estado actual
            $userId = Auth::id();
            $freshUser = \App\Models\Usuario::find($userId);
            
            // Si el usuario existe y está inactivo, lo desconectamos
            if ($freshUser && strtolower($freshUser->estado) === 'inactivo') {
                Auth::logout();
                
                // Clear session data
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                  return redirect()->route('login')
                    ->with('error', 'Tu cuenta ha sido desactivada por un administrador. Si crees que esto es un error, por favor contacta al soporte técnico.')
                    ->with('cuenta_desactivada', true);
            }
        }
        
        return $next($request);
    }
}
