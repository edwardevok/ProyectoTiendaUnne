<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categorias          = Category::all();
        $categoriasEliminadas = Category::onlyTrashed()->get();
        return view('admin.categorias', compact('categorias', 'categoriasEliminadas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create($validated);
        return redirect('/admin/categorias')->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $categoria = Category::findOrFail($id);
        $categoria->update($validated);
        return redirect('/admin/categorias')->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        $categoria = Category::findOrFail($id);

        if ($categoria->tieneProductos()) {
            return redirect('/admin/categorias')->with('error', 'No se puede eliminar la categoría "' . $categoria->name . '" porque tiene productos adentro. Eliminalos o movelos primero.');
        }

        $categoria->delete();
        return redirect('/admin/categorias')->with('success', 'Categoría eliminada correctamente.');
    }

    public function restore($id)
    {
        $categoria = Category::onlyTrashed()->findOrFail($id);
        $categoria->restore();
        return redirect('/admin/categorias')->with('success', 'Categoría "' . $categoria->name . '" restaurada correctamente.');
    }
}
