public function destroy($id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        // Apagamos el interruptor en la base de datos
        $user->is_active = 0;
        $user->save();
        
        // Suspendemos (SoftDelete)
        $user->delete(); 

        return redirect('/admin/usuarios')->with('success', 'Usuario suspendido correctamente.');
    }

    public function restore($id)
    {
        $user = \App\Models\User::withTrashed()->findOrFail($id); 
        
        // Prendemos el interruptor de nuevo
        $user->is_active = 1;
        $user->save();
        
        // Restauramos
        $user->restore(); 

        return redirect('/admin/usuarios')->with('success', 'Usuario restaurado correctamente.');
    }