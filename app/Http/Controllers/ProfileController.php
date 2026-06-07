<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class ProfileController extends Controller
{
    // 1. Mostrar la vista del perfil con sus pedidos
    public function index()
{
    $user = Auth::user();
    // Renombramos la variable a $pedidos para que coincida con la vista
    $pedidos = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->get();
    $messages = $user->messages()->orderBy('created_at', 'desc')->get();

    return view('perfil', compact('user', 'pedidos', 'messages'));
}

    // 2. Actualizar los datos personales del cliente
   public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255', // <-- Validamos
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->last_name = $request->last_name; // <-- Guardamos el apellido en la BD
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Tus datos personales fueron actualizados correctamente.');
    }
}