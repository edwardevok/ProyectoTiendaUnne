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
        // 1. Validaciones estrictas con seguridad de contraseña
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email:filter|max:255|unique:users',  //email: rfc,dns
            // Convertimos las reglas de password en un arreglo para usar Regex
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-zA-Z]/', // Regla: Debe contener al menos una letra
                'regex:/[A-Z]/',    // Regla: Debe contener al menos una letra mayúscula
                'confirmed'
            ],
        ], [
            // Mensajes de error personalizados en español
            'email.email' => 'El correo debe tener un formato válido (ej: tu@email.com).',
            'email.unique' => 'Este correo ya está registrado en la tienda.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra y una mayúscula.',
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
        $credentials = $request->validate([
            'email' => ['required', 'email:filter'],
            'password' => ['required'],
        ]);

        // 2. Intentamos iniciar sesión con esos datos
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. Verificamos el rol del usuario
            if (Auth::user()->role === 'admin') {
                // Si es administrador, lo mandamos directo a su panel
                return redirect()->intended('/admin/dashboard');
            }

            // CORREGIDO: Lo mandamos a '/index' en lugar de '/'
            return redirect()->intended('/index');
        }

        // Si los datos son incorrectos, lo devolvemos con un error
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
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