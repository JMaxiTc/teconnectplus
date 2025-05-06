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
        Schema::table('usuario', function (Blueprint $table) {
            // Asegurar que id_genero sea unsignedBigInteger
            $table->unsignedBigInteger('id_genero')->change();

            // Agregar la clave foránea nuevamente
            $table->foreign('id_genero')->references('id_genero')->on('genero')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['id_genero']);

            // Revertir el cambio de tipo de columna si es necesario
            $table->integer('id_genero')->change();
        });
    }
};
