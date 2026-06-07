<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Importamos el modelo necesario

class ProductController extends Controller
{
    // 1. Mostrar la lista de todos los productos en el panel admin
    public function index(Request $request)
    {
        // 1. Traemos TODAS las categorías reales de la base de datos para el desplegable
        $categorias = \App\Models\Category::all();

        // 2. Empezamos a armar la consulta de productos (con su categoría para que no falle)
        $query = \App\Models\Product::with('category');

        // 3. Si escribieron algo en el buscador de texto...
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 4. Si seleccionaron una categoría del desplegable...
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        // 5. Ejecutamos la búsqueda y traemos los resultados
        $productos = $query->get();

        // 6. Enviamos todo a la vista
        return view('admin.productos', compact('productos', 'categorias'));
    }

    // 2. Mostrar el formulario para crear un nuevo producto
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

    // 3. Guardar el nuevo producto en la base de datos
    public function store(Request $request)
    {
        // 1. Validamos (cambiamos 'category' por 'category_id')
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // Validamos que el ID exista
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = $request->all();

        // 2. Procesar imagen (misma lógica)
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $data['image'] = $imageName;
        }

        // 3. Creamos el producto (esto ya guardará el category_id)
        Product::create($data);

        return redirect('/admin/productos');
    }

    // 4. Eliminar un producto de la base de datos
    public function destroy($id)
    {
        // Buscamos el producto por su ID
        $producto = Product::findOrFail($id);
        
        // Si tiene una imagen asociada, la borramos de la carpeta public/img
        if ($producto->image) {
            $rutaImagen = public_path('img/' . $producto->image);
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        // Eliminamos el registro de la base de datos
        $producto->delete();

        // Redireccionamos a la lista de productos con un aviso si quisieras
        return redirect('/admin/productos');
    }

    // 5. Guardar los cambios realizados
    public function update(Request $request, $id)
    {
        // 1. Validamos usando 'category_id' en lugar del viejo 'category'
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id', // ¡Acá está el cambio clave!
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // 2. Buscamos el producto en la base de datos
        $producto = Product::findOrFail($id);
        $data = $request->all();

        // 3. Si el usuario subió una imagen NUEVA, la procesamos
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('img'), $imageName);
            $data['image'] = $imageName;
        }

        // 4. Actualizamos los datos en la base de datos
        $producto->update($data);

        // 5. Volvemos a la lista de productos
        return redirect('/admin/productos');
    }

    // ==========================================
    // 6. SECCIÓN PÚBLICA (Catálogo para clientes)
    // ==========================================
    public function catalogo()
    {
        // Traemos todos los productos
        $productos = Product::all(); 
        
        // NUEVO: Traemos todas las categorías de la base de datos
        $categorias = \App\Models\Category::all(); 

        // Las enviamos a la vista pública de 'productos.blade.php'
        return view('productos', compact('productos', 'categorias'));
    }
}