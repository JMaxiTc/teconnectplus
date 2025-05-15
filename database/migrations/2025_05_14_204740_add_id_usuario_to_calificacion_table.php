<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('calificacion', function (Blueprint $table) {
            // Agregar campo para ID del usuario que es evaluado (asesor)
            $table->unsignedInteger('id_usuario')->nullable()->after('puntuacion');
            
            // No usamos llave foránea por compatibilidad con la estructura existente
            // Ya que podría haber problemas con los tipos de datos o la configuración de InnoDB
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calificacion', function (Blueprint $table) {
            $table->dropColumn('id_usuario');
        });
    }
};
