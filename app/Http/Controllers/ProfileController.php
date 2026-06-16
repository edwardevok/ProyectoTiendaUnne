<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user     = Auth::user();
        $pedidos  = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get();
        $messages = $user->messages()->orderBy('created_at', 'desc')->get();

        return view('perfil', compact('user', 'pedidos', 'messages'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'             => 'required|string|max:255',
            'last_name'        => 'required|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password'     => [
                'nullable', 'required_with:current_password',
                'string', 'min:8', 'regex:/[A-Z]/', 'regex:/[0-9]/',
                'confirmed', 'different:current_password',
            ],
        ], [
            'new_password.required_with'     => 'Debes ingresar una nueva contraseña si colocaste la actual.',
            'current_password.required_with' => 'Debes ingresar tu contraseña actual para poder cambiarla.',
            'new_password.min'               => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.regex'             => 'La nueva contraseña debe contener al menos una mayúscula y un número.',
            'new_password.confirmed'         => 'La confirmación de la nueva contraseña no coincide.',
            'new_password.different'         => 'Introduciste la misma contraseña. Debe ser distinta a la actual.',
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.'])->withInput();
            }
            $user->cambiarContrasena($request->new_password);
        }

        $user->actualizarPerfil($request->name, $request->last_name);

        return redirect()->back()->with('success', '¡Tus datos fueron actualizados correctamente!');
    }
}
