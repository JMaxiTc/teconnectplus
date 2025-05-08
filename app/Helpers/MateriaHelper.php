<?php

namespace App\Helpers;

class MateriaHelper
{
    public static function getIconForMateria($nombre)
    {
    // Mapeo dinámico entre materia y su ícono
        $materiasIconos = [
            "PROGRAMACION ORIENTADA A OBJETOS" => "fas fa-code",
            "CONTABILIDAD FINANCIERA" => "fas fa-calculator",
            "PSICOLOGIA GENERAL" => "fas fa-brain",
            "ANATOMIA BASICA" => "fas fa-user",
            "DISEÑO ARQUITECTÓNICO" => "fas fa-drafting-compass",
            "BASES DE DATOS" => "fas fa-database",
            "MACROECONOMIA" => "fas fa-chart-line",
            "NEUROCIENCIA" => "fas fa-brain",
            "CALCULO DIFERENCIAL" => "fas fa-square-root-alt",
            "HISTORIA DEL ARTE" => "fas fa-palette",
            "MATEMATICAS" => "fas fa-square-root-alt",
            "FISICA" => "fas fa-atom",
            "QUIMICA" => "fas fa-flask",
            "ESTADISTICA" => "fas fa-chart-bar",
            "FILOSOFIA" => "fas fa-gavel",
            "LENGUAJE Y COMUNICACIÓN" => "fas fa-comments",
            "SOCIOLOGIA" => "fas fa-users",
            "ECONOMIA" => "fas fa-coins",
            "LITERATURA" => "fas fa-book-reader",
            "DERECHO" => "fas fa-balance-scale",
            "GEOGRAFIA" => "fas fa-globe-americas",
            "MUSICA" => "fas fa-music",
            "EDUCACION FÍSICA" => "fas fa-futbol",
            "PSICOLOGIA EDUCATIVA" => "fas fa-chalkboard-teacher",
            "INGLES" => "fas fa-language",
            "ESPAÑOL" => "fas fa-pencil-alt",
            "TEORIA DE LA INFORMACION" => "fas fa-server",
            "ADMINISTRACION DE EMPRESAS" => "fas fa-briefcase",
            "EMPRENDIMIENTO" => "fas fa-lightbulb",
            "REDES DE COMPUTADORAS" => "fas fa-network-wired",
            "SEGURIDAD INFORMATICA" => "fas fa-shield-alt",
            "DESARROLLO WEB" => "fas fa-laptop-code",
            "INTERIORISMO" => "fas fa-couch",
            "GESTION DE PROYECTOS" => "fas fa-project-diagram",
            "ALIMENTOS Y BEBIDAS" => "fas fa-utensils",
            "CUIDADO ANIMAL" => "fas fa-paw",
            "MEDICINA" => "fas fa-stethoscope",
            "BIOLOGIA" => "fas fa-leaf",
            "ZOOLOGIA" => "fas fa-paw",
            "AGRONOMIA" => "fas fa-seedling",
        ];
    

    // Convertir el nombre a mayúsculas
    $nombreUpper = strtoupper($nombre);

    // Buscar el ícono en el mapeo por contener el nombre en lugar de una coincidencia exacta
    foreach ($materiasIconos as $materia => $icono) {
        if (str_contains($nombreUpper, strtoupper($materia))) {
            return $icono;
        }
      }
      return "fas fa-book";
    }
}