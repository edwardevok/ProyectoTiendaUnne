<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    // 1. Mostrar la lista de todos los productos en el panel admin
    public function index(Request $request)
    {
        $categorias = \App\Models\Category::all();

        // PRODUCTOS ACTIVOS (SoftDeletes los oculta automáticamente)
        $query = \App\Models\Product::with('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $productos = $query->get();

        // NUEVO: PRODUCTOS INACTIVOS (Solo los que tienen deleted_at lleno)
        $productosInactivos = \App\Models\Product::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        // Enviamos las 3 variables a la vista
        return view('admin.productos', compact('productos', 'categorias', 'productosInactivos'));
    }

    public function create() 
    {
        $categorias = \App\Models\Category::all();
        return view('admin.productos_crear', compact('categorias'));
    }

    public function edit($id) 
    {
        $producto = \App\Models\Product::findOrFail($id);
        $categorias = \App\Models\Category::all();
        return view('admin.productos_crear', compact('producto', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', 
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $data['image'] = $imageName;
        }

        Product::create($data);

        return redirect('/admin/productos');
    }

    // ELIMINACIÓN (Ahora es SoftDelete automático)
    public function destroy($id)
    {
        $producto = Product::findOrFail($id);
        
        // Ya NO borramos la imagen física porque el producto sigue existiendo lógicamente
        
        // Eliminación lógica
        $producto->delete();

        return redirect('/admin/productos')->with('success', 'Producto desactivado correctamente.');
    }

    // NUEVO: RESTAURAR PRODUCTO
    public function restore($id)
    {
        //withTrashed() le dice a Laravel: "buscalo incluso si está eliminado"
        $producto = Product::withTrashed()->findOrFail($id); 
        $producto->restore(); // Limpia la columna deleted_at

        return redirect('/admin/productos')->with('success', 'Producto restaurado y activo.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', 
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $producto = Product::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $data['image'] = $imageName;
        }

        $producto->update($data);

        return redirect('/admin/productos');
    }

    public function catalogo()
    {
        // Automáticamente solo trae los activos
        $productos = Product::all(); 
        $categorias = \App\Models\Category::all(); 

        return view('productos', compact('productos', 'categorias'));
    }
}