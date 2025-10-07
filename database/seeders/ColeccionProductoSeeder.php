<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColeccionProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Colección 1 (Winter 2021)
            ['id_producto' => 1, 'id_coleccion' => 1],
            ['id_producto' => 2, 'id_coleccion' => 1],
            ['id_producto' => 4, 'id_coleccion' => 1],
            ['id_producto' => 7, 'id_coleccion' => 1],
            ['id_producto' => 9, 'id_coleccion' => 1],

            // Colección 2 (Winter 2020)
            ['id_producto' => 3, 'id_coleccion' => 2],
            ['id_producto' => 5, 'id_coleccion' => 2],
            ['id_producto' => 6, 'id_coleccion' => 2],
            ['id_producto' => 8, 'id_coleccion' => 2],
            ['id_producto' => 10, 'id_coleccion' => 2],
        ];

        DB::table('Coleccion_Producto')->insert($data);
    }
}
