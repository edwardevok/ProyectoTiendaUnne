<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // 1. CLIENTES ACTIVOS (Ahora con contador de pedidos)
        $clientesQuery = User::where('role', 'cliente')->withCount('orders');

        // Lógica del filtro de ordenamiento
        if ($request->sort == 'compras_desc') {
            $clientesQuery->orderBy('orders_count', 'desc');
        } elseif ($request->sort == 'compras_asc') {
            $clientesQuery->orderBy('orders_count', 'asc');
        } else {
            // Orden por defecto (los más recientes primero)
            $clientesQuery->orderBy('created_at', 'desc');
        }
        
        $clientes = $clientesQuery->get();

        // 2. ADMINS ACTIVOS
        $admins = User::where('role', 'admin')->where('email', '!=', 'root@tiendaunne.com')->get();

        // 3. USUARIOS INACTIVOS (Suspendidos/Eliminados)
        $clientesInactivos = User::onlyTrashed()->where('role', 'cliente')->get();
        $adminsInactivos = User::onlyTrashed()->where('role', 'admin')->get();

        return view('admin.usuarios', compact('clientes', 'admins', 'clientesInactivos', 'adminsInactivos'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->email === 'root@tiendaunne.com') {
            return redirect()->back()->withErrors(['error' => 'Acción denegada: Esta cuenta maestra del sistema no puede ser eliminada.']);
        }

        $user->delete(); // Suspende al usuario (SoftDelete)

        // Verificamos si era admin o cliente para el mensaje de éxito
        $mensaje = $user->role === 'cliente' ? 'Cliente suspendido correctamente.' : 'Administrador desactivado correctamente.';
        return redirect('/admin/usuarios')->with('success', $mensaje);
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        $mensaje = $user->role === 'cliente' ? 'Cliente reactivado exitosamente.' : 'Administrador restaurado y activo.';
        return redirect('/admin/usuarios')->with('success', $mensaje);
    }

    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email:filter|unique:users,email',
            'role' => 'required|string',
            'password' => [
                'required', 
                'string', 
                \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()
            ],
        ];

        $mensajes = [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debes ingresar un correo válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.mixed' => 'La contraseña debe tener al menos una letra mayúscula y una minúscula.',
        ];

        $request->validate($reglas, $mensajes);

        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'role' => $request->role,
        ]);

        return redirect('/admin/usuarios')->with('success', 'Administrador creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $reglas = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => [
                'nullable', 
                'string', 
                \Illuminate\Validation\Rules\Password::min(8)->letters()->mixedCase()
            ],
        ];

        $mensajes = [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya está en uso por otro usuario.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',
            'password.mixed' => 'La contraseña debe tener al menos una mayúscula y una minúscula.',
        ];

        $request->validate($reglas, $mensajes);

        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/admin/usuarios')->with('success', 'Administrador actualizado correctamente.');
    }
}