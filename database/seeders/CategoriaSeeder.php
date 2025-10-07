<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Categoria')->insert([
            ['id_categoria' => 1, 'Nombre' => 'Superiores'],
            ['id_categoria' => 2, 'Nombre' => 'Pantalones'],
            ['id_categoria' => 3, 'Nombre' => 'Accesorios'],
        ]);
    }
}
