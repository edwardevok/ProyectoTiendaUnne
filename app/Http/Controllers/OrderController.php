<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // ==========================================
    // SECCIÓN DEL CLIENTE (Checkout)
    // ==========================================

    public function checkout()
    {
        $cart      = session()->get('cart', []);
        $cartLimpio = Product::filtrarCarrito($cart);

        if (count($cartLimpio) < count($cart)) {
            session()->put('cart', $cartLimpio);
            return redirect('/carrito')->with('warning', 'Un producto que tenías en el carrito fue retirado del catálogo. Revisa tu nuevo total antes de continuar.');
        }

        if (empty($cartLimpio)) {
            return redirect('/productos')->with('error', 'Tu carrito está vacío.');
        }

        return view('checkout', ['cart' => $cartLimpio]);
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/productos')->with('error', 'Tu sesión de carrito expiró.');
        }

        $request->validate([
            'delivery_type' => 'required|in:campus,domicilio',
            'address'       => 'required_if:delivery_type,domicilio|nullable|string|max:255',
        ]);

        try {
            $orden = Order::crearDesdeCarrito(
                $cart,
                $request->delivery_type,
                $request->address,
                Auth::id()
            );

            session()->forget('cart');
            return redirect('/productos')->with('pedido_exitoso', $orden->id);

        } catch (\Exception $e) {
            return redirect('/carrito')->with('error', $e->getMessage());
        }
    }

    // ==========================================
    // SECCIÓN DEL ADMINISTRADOR (Panel)
    // ==========================================

    public function adminIndex(Request $request)
    {
        $pedidos = Order::filtrados(
            $request->input('search'),
            $request->input('status'),
            $request->input('sort_total')
        )->paginate(15)->appends($request->query());

        $pedidosEntregados = Order::entregados()
            ->with(['user', 'items.product'])
            ->orderBy('updated_at', 'desc')
            ->limit(100)
            ->get();

        return view('admin.pedidos', compact('pedidos', 'pedidosEntregados'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pendiente,en_preparacion,listo_para_retirar,enviado,entregado',
        ]);

        $pedido = Order::findOrFail($id);
        $pedido->actualizarEstado($request->status);

        return redirect()->back()->with('success', "El pedido #{$pedido->id} ahora está en estado: " . strtoupper(str_replace('_', ' ', $pedido->status)));
    }
}
