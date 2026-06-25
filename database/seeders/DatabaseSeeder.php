<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Carga los datos iniciales de la tienda.
     * El orden importa: las categorías primero (los productos las referencian por nombre).
     */
    public function run(): void
    {
        $this->call([
            CategoriaSeeder::class,
            ProductSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
