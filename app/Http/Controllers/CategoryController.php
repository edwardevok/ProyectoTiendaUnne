<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Listar categorías
    public function index()
{
    // Obtenemos todas las categorías de la BD
    $categorias = Category::all();
    return view('admin.categorias', compact('categorias'));
}

    // Guardar nueva categoría
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect('/admin/categorias');
    }

    // Eliminar categoría
    public function destroy($id)
    {
        $categoria = Category::findOrFail($id);

        // 1. Verificamos si la categoría tiene productos adentro
        if ($categoria->products()->count() > 0) {
            // Si tiene, lo frenamos y enviamos un mensaje de error
            return redirect('/admin/categorias')->with('error', 'No se puede eliminar la categoría "' . $categoria->name . '" porque tiene productos adentro. Elimina o mueve esos productos primero.');
        }

        // 2. Si no tiene productos, la borramos tranquilamente
        $categoria->delete();
        
        // 3. Volvemos con un mensaje de éxito
        return redirect('/admin/categorias')->with('success', 'Categoría eliminada correctamente.');
    }
    // Actualizar una categoría existente
    public function update(Request $request, $id)
    {
        // Validamos que no envíen el nombre vacío
        $request->validate(['name' => 'required|string|max:255']);
        
        // Buscamos la categoría y la actualizamos
        $categoria = Category::findOrFail($id);
        $categoria->update($request->all());
        
        // Volvemos a la página
        return redirect('/admin/categorias');
    }
}
