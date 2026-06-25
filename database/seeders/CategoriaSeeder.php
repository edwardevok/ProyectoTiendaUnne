<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categorias = [
        'Indumentaria',
        'Accesorios',
        'Librería',
        'Bazar'
    ];

    foreach ($categorias as $cat) {
        \App\Models\Category::create([
            'name' => $cat
        ]);
    }
}
}
