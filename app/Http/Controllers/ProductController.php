<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Catálogo público de productos (vista de clientes)
    public function catalogo(Request $request)
    {
        $query = Product::with('category')->activos();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $productos  = $query->orderBy('created_at', 'desc')->get();
        $categorias = Category::all();

        return view('productos', compact('productos', 'categorias'));
    }

    // Panel admin: lista de productos activos e inactivos con filtros
    public function index(Request $request)
    {
        $query = Product::with('category')->withSum('items', 'quantity')->activos();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('sort_price')) {
            $query->orderBy('price', $request->sort_price);
        } elseif ($request->filled('sort_stock')) {
            $query->orderBy('stock', $request->sort_stock);
        } elseif ($request->filled('sort_ventas')) {
            $query->orderBy('items_sum_quantity', $request->sort_ventas);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $productos          = $query->get();
        $productosInactivos = Product::onlyTrashed()->with('category')->get();
        $categorias         = Category::all();

        return view('admin.productos', compact('productos', 'productosInactivos', 'categorias'));
    }

    // Formulario para crear un nuevo producto
    public function create()
    {
        $categorias = Category::all();
        return view('admin.productos_crear', compact('categorias'));
    }

    // Guardar un nuevo producto
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $archivo   = $request->file('image');
            $extension = $archivo->getClientOriginalExtension();
            $nombre    = uniqid('prod_', true) . '.' . $extension;
            $archivo->move(public_path('img'), $nombre);
            $validated['image'] = $nombre;
        }

        $validated['is_active'] = 1;
        Product::create($validated);

        return redirect('/admin/productos')->with('success', 'Producto creado correctamente.');
    }

    // Formulario para editar un producto existente
    public function edit($id)
    {
        $producto   = Product::findOrFail($id);
        $categorias = Category::all();
        return view('admin.productos_crear', compact('producto', 'categorias'));
    }

    // Actualizar un producto existente
    public function update(Request $request, $id)
    {
        $producto = Product::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $archivo   = $request->file('image');
            $extension = $archivo->getClientOriginalExtension();
            $nombre    = uniqid('prod_', true) . '.' . $extension;
            $archivo->move(public_path('img'), $nombre);
            $validated['image'] = $nombre;
        }

        $producto->update($validated);
        return redirect('/admin/productos')->with('success', 'Producto actualizado correctamente.');
    }

    // Desactivar producto (soft delete + is_active = 0)
    public function destroy($id)
    {
        $producto = Product::findOrFail($id);
        $producto->desactivar();
        return redirect('/admin/productos')->with('success', 'Producto desactivado correctamente.');
    }

    // Restaurar producto (restore + is_active = 1)
    public function restore($id)
    {
        $producto = Product::withTrashed()->findOrFail($id);
        $producto->restaurar();
        return redirect('/admin/productos')->with('success', 'Producto restaurado y activo.');
    }
}
