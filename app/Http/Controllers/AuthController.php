<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // --- LÓGICA DE REGISTRO ---
    public function register(Request $request)
    {
        // 1. Validaciones estrictas con seguridad de contraseña y mensajes separados
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email:filter|max:255|unique:users', 
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                // Nuestro validador personalizado (Closure)
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('A tu contraseña le falta al menos una letra MAYÚSCULA.');
                    }
                    if (!preg_match('/[a-z]/', $value)) {
                        $fail('A tu contraseña le falta al menos una letra minúscula.');
                    }
                    if (!preg_match('/[0-9]/', $value)) {
                        $fail('A tu contraseña le falta al menos un número.');
                    }
                },
            ],
        ], [
            // Mensajes de error personalizados en español para las reglas generales
            'email.email' => 'El correo debe tener un formato válido (ej: tu@email.com).',
            'email.unique' => 'Este correo ya está registrado en la tienda.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // 2. Crear el usuario (La contraseña se encripta con Hash::make)
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'cliente', 
        ]);

        // 3. Iniciar sesión automáticamente y redirigir
        Auth::login($user);
        return redirect('/index');
    }

    // --- LÓGICA DE LOGIN ---
    public function login(Request $request)
    {
        // 1. Validamos que el usuario haya escrito algo
        $request->validate([
            'email' => ['required', 'email:filter'],
            'password' => ['required'],
        ]);

        // 2. Buscamos al usuario en la base de datos por su correo
        $user = User::where('email', $request->email)->first();

        // 3. Verificamos si el correo NO existe
        if (!$user) {
            return back()->withErrors([
                'email' => 'El correo ingresado no está registrado.',
            ])->onlyInput('email'); // Mantiene el email escrito
        }

        // 4. Si el correo existe, verificamos si la contraseña NO coincide
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'La contraseña ingresada es incorrecta.',
            ])->onlyInput('email'); // Mantiene el email escrito
        }

        // 5. Si pasamos los dos controles anteriores, iniciamos sesión
        Auth::login($user);
        $request->session()->regenerate();

        // Verificamos el rol para redirigir
        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/index');
    }

    // --- LÓGICA DE LOGOUT ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Al cerrar sesión, también lo mandamos al catálogo
        return redirect('/index');
    }
}