<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genero', function (Blueprint $table) {
            $table->id('id_genero');
            $table->string('genero', 50);
        });
        
        // Insertar géneros predefinidos
        DB::table('genero')->insert([
            ['genero' => 'Masculino'],
            ['genero' => 'Femenino'],
            ['genero' => 'No binario'],
            ['genero' => 'Prefiero no decirlo'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genero');
    }
};
