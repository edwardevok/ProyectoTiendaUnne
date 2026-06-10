<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // 1. Mostrar la vista del carrito con limpieza automática de eliminados
    public function index()
    {
        $cart = session()->get('cart', []);
        $cartModificado = false;

        if (count($cart) > 0) {
            // Obtenemos todos los IDs que el cliente tiene en su carrito
            $idsEnCarrito = array_keys($cart);
            
            // Buscamos en la base de datos cuáles de esos IDs siguen "vivos" (no eliminados)
            $productosActivos = Product::whereIn('id', $idsEnCarrito)->pluck('id')->toArray();

            // Recorremos el carrito. Si un ID no está en los activos, lo borramos de la sesión.
            foreach ($cart as $id => $details) {
                if (!in_array($id, $productosActivos)) {
                    unset($cart[$id]);
                    $cartModificado = true;
                }
            }

            // Si tuvimos que limpiar algún producto, guardamos los cambios y avisamos
            if ($cartModificado) {
                session()->put('cart', $cart);
                session()->flash('warning', 'Atención: Algunos productos de tu carrito ya no están disponibles en la tienda y fueron retirados automáticamente.');
            }
        }

        return view('carrito', compact('cart'));
    }

   // 2. Agregar un producto al carrito (con control de stock y cantidad)
    public function add(Request $request, $id)
    {
        $producto = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Obtenemos la cantidad que el cliente escribió en el cuadrito (por defecto 1)
        $requestedQuantity = $request->input('quantity', 1);

        // 2. Revisamos si este producto ya estaba en el carrito y qué cantidad tenía
        $currentCartQuantity = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;

        // 3. Calculamos el total que quiere tener (lo que ya tenía + lo que quiere agregar ahora)
        $newTotalQuantity = $currentCartQuantity + $requestedQuantity;

        // 4. ¡BARRERA DE STOCK! Verificamos si nos alcanza la mercadería
        if ($newTotalQuantity > $producto->stock) {
            
            // Si el stock está en cero absoluto
            if ($producto->stock == 0) {
                return redirect()->back()->with('error', 'Lo sentimos, este producto se encuentra agotado.');
            } 
            // Si ya tiene algunos en el carrito pero quiere agregar más de los que quedan
            elseif ($currentCartQuantity > 0) {
                $disponible = $producto->stock - $currentCartQuantity;
                return redirect()->back()->with('error', "No hay suficiente stock. Ya tenés $currentCartQuantity en tu carrito y solo podés agregar $disponible unidad(es) más.");
            } 
            // Si directamente pidió de golpe más de lo que hay
            else {
                return redirect()->back()->with('error', "No hay suficiente stock. Solo nos quedan $producto->stock unidad(es) disponibles de este producto.");
            }
        }

        // 5. Si pasamos la barrera de stock, agregamos al carrito
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $requestedQuantity;
        } else {
            $cart[$id] = [
                "name" => $producto->name,
                "quantity" => $requestedQuantity,
                "price" => $producto->price,
                "image" => $producto->image
            ];
        }

        session()->put('cart', $cart);
        
        return redirect()->back()->with('success', "¡Se agregaron $requestedQuantity unidad(es) de {$producto->name} al carrito!");
    }

    // 3. Quitar un producto específico del carrito
    public function remove($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]); // Lo borramos del array
            session()->put('cart', $cart); // Guardamos el carrito actualizado
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    // 4. Vaciar el carrito por completo
    public function clear()
    {
        session()->forget('cart'); // Borra la variable de la sesión
        return redirect()->back()->with('success', 'El carrito ha sido vaciado.');
    }
}