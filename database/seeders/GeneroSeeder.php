<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeneroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya hay registros en la tabla
        $count = DB::table('genero')->count();
        
        if ($count == 0) {
            DB::table('genero')->insert([
                ['id_genero' => 1, 'genero' => 'FEMENINO', 'descripcion' => 'Género femenino'],
                ['id_genero' => 2, 'genero' => 'MASCULINO', 'descripcion' => 'Género masculino'],
                ['id_genero' => 3, 'genero' => 'NO BINARIO', 'descripcion' => 'Género no binario'],
            ]);
            $this->command->info('Tabla genero poblada correctamente.');
        } else {
            $this->command->info('La tabla genero ya tiene registros.');
        }
    }
}
