<?php

namespace App\Helpers;

class MaterialesHelper
{
    public static function getIconForTipo($tipo)
    {
        $tiposIconos = [
            "pdf" => "fas fa-file-pdf text-red-500",
            "video" => "fas fa-video text-indigo-500",
            "enlace" => "fas fa-link text-blue-500",
            "imagen" => "fas fa-image text-pink-500",
            "presentación" => "fas fa-file-powerpoint text-orange-500",
            "documento" => "fas fa-file-word text-blue-600",
            "excel" => "fas fa-file-excel text-green-600",
            "otro" => "fas fa-file text-gray-500",
        ];

        $tipoLower = strtolower($tipo);

        return $tiposIconos[$tipoLower] ?? "fas fa-file text-gray-500";
    }
}
