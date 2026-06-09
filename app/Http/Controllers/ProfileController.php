<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class ProfileController extends Controller
{
    // 1. Mostrar la vista del perfil con sus pedidos (Queda intacto)
    public function index()
    {
        $user = Auth::user();
        // Renombramos la variable a $pedidos para que coincida con la vista
        $pedidos = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get();
        $messages = $user->messages()->orderBy('created_at', 'desc')->get();

        return view('perfil', compact('user', 'pedidos', 'messages'));
    }

    // 2. Actualizar los datos personales y de seguridad del cliente
    public function update(Request $request)
    {
        // 1. Obtenemos al usuario que está logueado
        $user = Auth::user();

        // 2. Validamos los datos que llegan del formulario unificado
        $request->validate([
            // Nombre y apellido
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255', 
            
            // Lógica de contraseña: Si llena uno, el otro es obligatorio
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => [
                'nullable',
                'required_with:current_password',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'confirmed',
                'different:current_password' // Regla para que no ponga la misma
            ],
        ], [
            // 3. Mensajes de error personalizados
            'new_password.required_with' => 'Debes ingresar una nueva contraseña si colocaste la actual.',
            'current_password.required_with' => 'Debes ingresar tu contraseña actual para poder cambiarla.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.regex' => 'La nueva contraseña debe contener al menos una mayúscula y un número.',
            'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            'new_password.different' => 'Introduciste la misma contraseña. Debe ser distinta a la actual.',
        ]);

        // 4. Verificamos la contraseña actual (SOLO si el usuario intentó cambiarla)
        if ($request->filled('current_password')) {
            // Comparamos lo que tipeó el usuario con la contraseña encriptada de la BD
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors([
                    'current_password' => 'La contraseña actual es incorrecta.'
                ])->withInput(); 
            }

            // Si pasó la prueba de seguridad, encriptamos y preparamos la clave nueva
            $user->password = Hash::make($request->new_password);
        }

        // 5. Actualizamos los datos personales (El email ya no se toca por seguridad)
        $user->name = $request->name;
        $user->last_name = $request->last_name;

        // 6. Guardamos los cambios finales en Workbench
        $user->save();

        // 7. Devolvemos al usuario con el cartel de éxito verde
        return redirect()->back()->with('success', '¡Tus datos fueron actualizados correctamente!');
    }
}