<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ColeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('Coleccion')->insert([
            ['id_coleccion' => 1, 'Nombre' => 'Winter 2021','Año' => '2021'],
            ['id_coleccion' => 2, 'Nombre' => 'Winter 2020','Año' => '2020'],
        ]);
    }
}
