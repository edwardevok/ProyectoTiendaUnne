<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // Mostrar la pantalla con las dos columnas
    public function index()
    {
        // Buscamos a los clientes y a los admins por separado (excluyendo al fantasma de la lista de admins)
        $clientes = User::where('role', 'cliente')->get();
        $admins = User::where('role', 'admin')->where('email', '!=', 'root@tiendaunne.com')->get();

        // Los enviamos a la vista
        return view('admin.usuarios', compact('clientes', 'admins'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // BARRERA DE SEGURIDAD: Si intentan borrar al fantasma mediante una URL, lo frenamos en seco
        if ($user->email === 'root@tiendaunne.com') {
            return redirect()->back()->withErrors(['error' => 'Acción denegada: Esta cuenta maestra del sistema no puede ser eliminada.']);
        }

        $user->delete();

        return redirect('/admin/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    // Guardar un nuevo administrador desde la ventanita
    public function store(Request $request)
    {
        // 1. Las reglas estrictas
        $reglas = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email:filter|unique:users,email',
            'role' => 'required|string',
            'password' => [
                'required', 
                'string', 
                \Illuminate\Validation\Rules\Password::min(8)
                    ->letters()
                    ->mixedCase()
            ],
        ];

        // 2. Tus traducciones personalizadas en español
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

        // 3. Ejecutamos la validación usando ambas listas
        $request->validate($reglas, $mensajes);

        // 4. Si pasa la validación, creamos el usuario
        User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password), 
            'role' => $request->role,
        ]);

        return redirect('/admin/usuarios');
    }

    // Actualizar un administrador existente
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $reglas = [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // El '.$id' al final le dice a Laravel: "El email debe ser único, EXCEPTO si es el mismo que ya tiene este usuario"
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => [
                'nullable', // ¡La contraseña es opcional al editar!
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

        // Actualizamos los datos básicos
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        // Si escribió algo en el campo de contraseña, la encriptamos y la cambiamos
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/admin/usuarios')->with('success', 'Administrador actualizado correctamente.');
    }

    
}