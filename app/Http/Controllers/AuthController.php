<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|string|email:filter|max:255|unique:users',
            'password'  => [
                'required', 'string', 'min:8', 'confirmed',
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
            'email.email'    => 'El correo debe tener un formato válido (ej: tu@email.com).',
            'email.unique'   => 'Este correo ya está registrado en la tienda.',
            'password.min'   => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = User::registrar($request->only('name', 'last_name', 'email', 'password'));

        Auth::login($user);
        return redirect('/index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email:filter'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        // Mensaje unificado para evitar user enumeration (no revelar si el email existe o no)
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Las credenciales ingresadas no coinciden con nuestros registros.',
            ])->onlyInput('email');
        }

        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Tu cuenta está desactivada. Contactate con soporte.',
            ])->onlyInput('email');
        }

        Auth::login($user);
        $request->session()->regenerate();

        // Restaurar carrito guardado en la BD y mergearlo con el de la sesión actual.
        // json_decode devuelve claves string ("15"), la sesión usa claves int (15).
        // Normalizamos a int y usamos + (no array_merge) para preservar las claves.
        $cartSesion      = session()->get('cart', []);
        $cartGuardadoRaw = $user->cart ? json_decode($user->cart, true) : [];
        $cartGuardado    = [];
        foreach ($cartGuardadoRaw as $id => $item) {
            $cartGuardado[(int) $id] = $item;
        }
        // + preserva claves; la sesión (izquierda) tiene prioridad sobre la BD (derecha).
        $cartFinal = $cartSesion + $cartGuardado;
        if (!empty($cartFinal)) {
            session()->put('cart', $cartFinal);
        }

        return redirect()->intended($user->esAdmin() ? '/admin/dashboard' : '/index');
    }

    public function logout(Request $request)
    {
        // Guardar el carrito en la BD antes de destruir la sesión.
        if (Auth::check()) {
            $user       = Auth::user();
            $user->cart = json_encode(session()->get('cart', []));
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/index');
    }
}
