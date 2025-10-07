<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriaProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Productos 1, 2, 9 son Accesorios (ID 3)
            ['id_producto' => 1, 'id_categoria' => 3],
            ['id_producto' => 2, 'id_categoria' => 3],
            ['id_producto' => 9, 'id_categoria' => 3],

            // Productos 4, 5, 6 son Superiores (ID 1)
            ['id_producto' => 4, 'id_categoria' => 1],
            ['id_producto' => 5, 'id_categoria' => 1],
            ['id_producto' => 6, 'id_categoria' => 1],

            // Productos 7, 8, 10 son Pantalones (ID 2)
            ['id_producto' => 7, 'id_categoria' => 2],
            ['id_producto' => 8, 'id_categoria' => 2],
            ['id_producto' => 10, 'id_categoria' => 2],

            // Producto 3 (un accesorio adicional)
            ['id_producto' => 3, 'id_categoria' => 3],
        ];

        DB::table('Categoria_Producto')->insert($data);
    }
}
