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
        Schema::create('notificacions', function (Blueprint $table) {
            $table->id();
            $table->integer('id_usuario')->comment('ID del usuario que recibe la notificación');
            $table->string('titulo', 255);
            $table->text('mensaje');
            $table->string('tipo', 50)->default('info')->comment('Tipo de notificación: info, success, warning, error');
            $table->string('icono', 100)->nullable();
            $table->string('url', 255)->nullable()->comment('URL opcional para redirigir al hacer clic');
            $table->boolean('leida')->default(false);
            $table->timestamps();
            
            // Añadir indexación para mejores consultas
            $table->index('id_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacions');
    }
};
