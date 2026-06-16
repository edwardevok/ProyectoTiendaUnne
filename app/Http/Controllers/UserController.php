<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'recientes');

        $clientesQuery = User::where('role', 'cliente')->withCount('orders');

        if ($sort === 'compras_desc') {
            $clientesQuery->orderBy('orders_count', 'desc');
        } elseif ($sort === 'compras_asc') {
            $clientesQuery->orderBy('orders_count', 'asc');
        } else {
            $clientesQuery->orderBy('created_at', 'desc');
        }

        $clientes          = $clientesQuery->get();
        $clientesInactivos = User::onlyTrashed()->where('role', 'cliente')->orderBy('deleted_at', 'desc')->get();
        $admins            = User::where('role', 'admin')->orderBy('name')->get();

        return view('admin.usuarios', compact('clientes', 'clientesInactivos', 'admins'));
    }

    // Crear un nuevo administrador
    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|email:filter|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        User::registrar($request->only('name', 'last_name', 'email', 'password'), 'admin');

        return redirect('/admin/usuarios')->with('success', 'Administrador creado correctamente.');
    }

    // Actualizar datos de un usuario (admin o cliente)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email'     => 'required|email:filter|max:255|unique:users,email,' . $id,
        ]);

        $user->actualizarPerfil($request->name, $request->last_name);
        $user->email = $request->email;
        $user->save();

        return redirect('/admin/usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    // Suspender usuario (soft delete + is_active = 0)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->suspender();
        return redirect('/admin/usuarios')->with('success', 'Usuario suspendido correctamente.');
    }

    // Restaurar usuario (restore + is_active = 1)
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restaurar();
        return redirect('/admin/usuarios')->with('success', 'Usuario restaurado correctamente.');
    }
}
