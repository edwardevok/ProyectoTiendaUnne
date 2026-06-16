<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Persiste el carrito en la columna cart del usuario logueado.
    private function guardarEnBD(array $cart): void
    {
        if (auth()->check()) {
            $user       = auth()->user();
            $user->cart = json_encode($cart);
            $user->save();
        }
    }

    public function index()
    {
        $cart       = session()->get('cart', []);
        $cartLimpio = Product::filtrarCarrito($cart);

        if (count($cartLimpio) < count($cart)) {
            session()->put('cart', $cartLimpio);
            $this->guardarEnBD($cartLimpio);
            session()->flash('warning', 'Atención: Algunos productos de tu carrito ya no están disponibles y fueron retirados automáticamente.');
        }

        return view('carrito', ['cart' => $cartLimpio]);
    }

    public function add(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $producto       = Product::findOrFail($id);
        $cart           = session()->get('cart', []);
        $cantidadActual = $cart[$id]['quantity'] ?? 0;
        $nuevaCantidad  = $cantidadActual + (int) $request->quantity;

        if ($nuevaCantidad > $producto->stock) {
            if ($producto->stock === 0) {
                $mensaje = 'Lo sentimos, este producto se encuentra agotado.';
            } elseif ($cantidadActual > 0) {
                $disponible = $producto->stock - $cantidadActual;
                $mensaje = "No hay suficiente stock. Ya tenés {$cantidadActual} en tu carrito y solo podés agregar {$disponible} unidad(es) más.";
            } else {
                $mensaje = "No hay suficiente stock. Solo nos quedan {$producto->stock} unidad(es) disponibles.";
            }

            if ($request->ajax()) {
                return response()->json(['type' => 'error', 'message' => $mensaje]);
            }
            return redirect()->back()->with('error', $mensaje);
        }

        $cart[$id] = [
            'name'     => $producto->name,
            'quantity' => $nuevaCantidad,
            'price'    => $producto->price,
            'image'    => $producto->image,
        ];

        session()->put('cart', $cart);
        $this->guardarEnBD($cart);

        $totalUnidades = array_sum(array_column($cart, 'quantity'));
        $mensaje       = "¡Se agregaron {$request->quantity} unidad(es) de {$producto->name} al carrito!";

        if ($request->ajax()) {
            return response()->json([
                'type'          => 'success',
                'message'       => $mensaje,
                'totalUnidades' => $totalUnidades,
            ]);
        }

        return redirect()->back()->with('success', $mensaje);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        $this->guardarEnBD($cart);

        return redirect()->back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear()
    {
        session()->forget('cart');
        $this->guardarEnBD([]);

        return redirect()->back()->with('success', 'El carrito ha sido vaciado.');
    }
}
