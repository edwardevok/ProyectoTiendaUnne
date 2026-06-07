<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Tarjetas Superiores (Métricas)
        // Contamos todos los pedidos que NO están finalizados
        $pedidosPendientes = Order::whereIn('status', ['pendiente', 'en_preparacion', 'listo_para_retirar'])->count();
        
        // Calculamos el promedio de gasto por compra (Si no hay ventas, devuelve 0)
        $ticketMedio = Order::avg('total') ?? 0;
        
        // Contamos la cantidad de usuarios registrados en el sistema
        $usuariosRegistrados = User::count();
        
        // Contamos solo los pedidos que ya llegaron a su dueño
        $pedidosEntregados = Order::where('status', 'entregado')->count();


        // 2. Tabla de Últimos 5 Pedidos
        $ultimosPedidos = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();


        // 3. Ranking de los Top 5 Productos más vendidos
        // Unimos la tabla de detalle_pedidos con productos para sumar las cantidades
        $topProductos = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_vendido'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();

        // Enviamos todas estas variables matemáticas a la vista
        return view('admin.dashboard', compact(
            'pedidosPendientes', 
            'ticketMedio', 
            'usuariosRegistrados', 
            'pedidosEntregados', 
            'ultimosPedidos', 
            'topProductos'
        ));
    }
}