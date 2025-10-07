<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Aseguramos que se creen primero las tablas principales
            //CategoriaSeeder::class,
            ColeccionSeeder::class,
            // Y después las tablas pivote
            CategoriaProductoSeeder::class,
            ColeccionProductoSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
