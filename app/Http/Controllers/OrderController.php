<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ==========================================
    // SECCIÓN DEL CLIENTE (Checkout)
    // ==========================================

    // 1. Mostrar la pantalla de Checkout con limpieza de carrito
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        // Limpiamos los eliminados antes de dejarlo pagar
        if (count($cart) > 0) {
            $idsEnCarrito = array_keys($cart);
            $productosActivos = Product::whereIn('id', $idsEnCarrito)->pluck('id')->toArray();
            $cartModificado = false;

            foreach ($cart as $id => $details) {
                if (!in_array($id, $productosActivos)) {
                    unset($cart[$id]);
                    $cartModificado = true;
                }
            }

            if ($cartModificado) {
                session()->put('cart', $cart);
                // Si le sacamos algo, lo mandamos de vuelta al carrito para que vea el nuevo precio total
                return redirect('/carrito')->with('warning', 'Un producto que tenías en el carrito fue retirado del catálogo. Revisa tu nuevo total antes de continuar.');
            }
        }

        if (count($cart) == 0) {
            return redirect('/productos')->with('error', 'Tu carrito está vacío.');
        }

        return view('checkout', compact('cart'));
    }

    // 2. Procesar la compra (Simulación de pago y descuento de stock)
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (count($cart) == 0) {
            return redirect('/productos')->with('error', 'Tu sesión de carrito expiró.');
        }

        $request->validate([
            'delivery_type' => 'required|in:campus,domicilio',
            'address' => 'required_if:delivery_type,domicilio|nullable|string|max:255',
        ]);

        $total = 0;
        foreach($cart as $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $direccionFinal = 'campusUNNE';
        if ($request->delivery_type == 'domicilio') {
            $direccionFinal = $request->address;
        }

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'delivery_type' => $request->delivery_type,
                'address' => $direccionFinal,
                'status' => 'pendiente'
            ]);

            foreach ($cart as $id => $details) {
                // Usamos find() en lugar de findOrFail para atrapar a los fantasmas con un mensaje personalizado
                $producto = Product::lockForUpdate()->find($id);

                if (!$producto) {
                    throw new \Exception("Lo sentimos, el producto '{$details['name']}' fue retirado del catálogo justo antes de tu pago. Revisa tu carrito.");
                }

                if ($producto->stock < $details['quantity']) {
                    throw new \Exception("Lo sentimos, nos quedamos sin stock de {$producto->name} mientras procesabas el pago.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ]);

                $producto->stock -= $details['quantity'];
                $producto->save();
            }

            DB::commit();
            session()->forget('cart');

            return redirect('/productos')->with('success', "¡Pedido #{$order->id} realizado con éxito! Su pedido está en preparación.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/carrito')->with('error', $e->getMessage());
        }
    }

    // ==========================================
    // SECCIÓN DEL ADMINISTRADOR (Panel)
    // ==========================================

    // 3. Mostrar la lista de pedidos en el panel Admin (con búsqueda y filtros)
    public function adminIndex(Request $request)
    {
        // 1. CONSULTA PRINCIPAL: Solo pedidos que NO estén entregados
        $query = Order::with(['user', 'items.product'])->where('status', '!=', 'entregado');

        // Búsqueda por número
        if ($request->filled('search')) {
            $search = str_replace('#', '', $request->search);
            $query->where('id', 'like', "%{$search}%");
        }

        // Filtro por estado
        if ($request->filled('status') && $request->status != 'Todos los estados') {
            $statusDb = str_replace(' ', '_', strtolower($request->status));
            $query->where('status', $statusDb);
        }

        // Filtro por valor total
        if ($request->filled('sort_total')) {
            if ($request->sort_total == 'desc') {
                $query->orderBy('total', 'desc'); 
            } elseif ($request->sort_total == 'asc') {
                $query->orderBy('total', 'asc'); 
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $pedidos = $query->paginate(15)->appends(request()->query());

        // 2. CONSULTA SECUNDARIA: Pedidos Entregados (Historial para el acordeón)
        // Usamos limit(100) como buena práctica para no colapsar la memoria si tenés miles de ventas
        $pedidosEntregados = Order::with(['user', 'items.product'])
            ->where('status', 'entregado')
            ->orderBy('updated_at', 'desc')
            ->limit(100) 
            ->get();

        return view('admin.pedidos', compact('pedidos', 'pedidosEntregados'));
    }

    // 4. Actualizar el estado de un pedido al instante
    public function updateStatus(Request $request, $id)
    {
        $pedido = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pendiente,en_preparacion,listo_para_retirar,enviado,entregado'
        ]);

        $pedido->status = $request->status;

        if (in_array($request->status, ['enviado', 'entregado']) && !$pedido->dispatched_at) {
            $pedido->dispatched_at = now();
        }

        $pedido->save();

        return redirect()->back()->with('success', "El pedido #{$pedido->id} ahora está en estado: " . strtoupper(str_replace('_', ' ', $pedido->status)));
    }
}