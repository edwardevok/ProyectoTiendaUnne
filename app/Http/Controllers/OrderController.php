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

    // 1. Mostrar la pantalla de Checkout
    public function checkout()
    {
        $cart = session()->get('cart', []);
        
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
                $producto = Product::lockForUpdate()->findOrFail($id);

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
        // Le quitamos el orderBy por defecto de acá para que sea dinámico
        $query = Order::with(['user', 'items.product']);

        // Si el admin escribió algo en el buscador
        if ($request->filled('search')) {
            $search = str_replace('#', '', $request->search);
            $query->where('id', 'like', "%{$search}%");
        }

        // Si el admin filtró por estado
        if ($request->filled('status') && $request->status != 'Todos los estados') {
            $statusDb = str_replace(' ', '_', strtolower($request->status));
            $query->where('status', $statusDb);
        }

        // NUEVO: Si el admin filtró por valor total (Mayor o Menor)
        if ($request->filled('sort_total')) {
            if ($request->sort_total == 'desc') {
                $query->orderBy('total', 'desc'); // Los más caros primero
            } elseif ($request->sort_total == 'asc') {
                $query->orderBy('total', 'asc'); // Los más baratos primero
            }
        } else {
            // Orden por defecto: los pedidos más nuevos arriba de todo
            $query->orderBy('created_at', 'desc');
        }

        // El appends(request()->query()) asegura que si el admin pasa a la página 2, 
        // no se pierdan los filtros aplicados (estado, búsqueda o precio)
        $pedidos = $query->paginate(15)->appends(request()->query());

        return view('admin.pedidos', compact('pedidos'));
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