<?php

public function destroy($id)
    {
        $producto = \App\Models\Product::findOrFail($id);
        
        // Apagamos el interruptor
        $producto->is_active = 0;
        $producto->save();
        
        // Desactivamos (SoftDelete)
        $producto->delete(); 

        return redirect('/admin/productos')->with('success', 'Producto desactivado correctamente.');
    }

    public function restore($id)
    {
        $producto = \App\Models\Product::withTrashed()->findOrFail($id); 
        
        // Prendemos el interruptor
        $producto->is_active = 1;
        $producto->save();
        
        // Restauramos
        $producto->restore(); 

        return redirect('/admin/productos')->with('success', 'Producto restaurado y activo.');
    }