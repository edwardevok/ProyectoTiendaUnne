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

        // Empezamos la consulta
        $query = \App\Models\Product::with('category');

        // Búsqueda por texto
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoría
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // NUEVO: Ordenamiento 1 - Por Precio
        if ($request->filled('sort_price')) {
            $query->orderBy('price', $request->sort_price); // 'desc' o 'asc'
        }

        // NUEVO: Ordenamiento 2 - Por Stock (Totalmente acumulable con el precio)
        if ($request->filled('sort_stock')) {
            $query->orderBy('stock', $request->sort_stock); // 'desc' o 'asc'
        }

        // Orden por defecto: Solo se aplica si el admin NO seleccionó precio ni stock
        if (!$request->filled('sort_price') && !$request->filled('sort_stock')) {
            $query->orderBy('id', 'desc');
        }

        // Traemos los productos listos
        $productos = $query->get();

        // Productos Inactivos (ocultos por SoftDeletes)
        $productosInactivos = \App\Models\Product::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

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

    public function destroy($id)
    {
        $producto = Product::findOrFail($id);
        $producto->delete(); // SoftDelete

        return redirect('/admin/productos')->with('success', 'Producto desactivado correctamente.');
    }

    public function restore($id)
    {
        $producto = Product::withTrashed()->findOrFail($id); 
        $producto->restore(); 

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
        $productos = Product::all(); 
        $categorias = \App\Models\Category::all(); 

        return view('productos', compact('productos', 'categorias'));
    }
}