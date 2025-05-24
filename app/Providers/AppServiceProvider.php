<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Facade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Definir el gate para el rol ADMIN
        Gate::define('ADMIN', function ($user) {
            return $user->rol === 'ADMIN';  // Compara el campo 'rol' de la tabla 'usuarios'
        });

        Gate::define('ESTUDIANTE', function ($user) {
        return $user->rol === 'ESTUDIANTE' || $user->rol === 'ADMIN';
        });

        Gate::define('ASESOR', function ($user) {
            return $user->rol === 'ASESOR' || $user->rol === 'ADMIN';  // Compara el campo 'rol' de la tabla 'usuarios'
        });
        
        // Register facade aliases for PDF
        Facade::clearResolvedInstance('PDF');
        $this->app->bind('PDF', function ($app) {
            return new \Barryvdh\DomPDF\PDF($app);
        });
    }
}
