<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

/**
 * Crea el usuario administrador con credenciales conocidas para la entrega.
 *
 *   Correo:     admin@tienda.com
 *   Contrasena: admin1234
 *
 * Usa User::registrar(), que asigna el rol 'admin' e is_active = 1 (el campo
 * 'role' no es fillable a proposito, por seguridad). La contrasena se encripta
 * automaticamente por el cast 'hashed' del modelo.
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (User::where('email', 'admin@tienda.com')->exists()) {
            return;
        }

        User::registrar([
            'name'      => 'Administrador',
            'last_name' => 'Tienda UNNE',
            'email'     => 'admin@tienda.com',
            'password'  => 'admin1234',
        ], 'admin');
    }
}
