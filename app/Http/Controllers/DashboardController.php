<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $pedidosPendientes      = Order::pendientes()->count();
        $ticketMedio            = Order::avg('total') ?? 0;
        $usuariosRegistrados    = User::count();
        $pedidosEntregados      = Order::entregados()->count();
        $ultimosPedidos         = Order::ultimos(5);
        $topProductos           = Product::masVendidos(5);
        $categoriasMasVendidas  = Category::masVendidas();

        return view('admin.dashboard', compact(
            'pedidosPendientes',
            'ticketMedio',
            'usuariosRegistrados',
            'pedidosEntregados',
            'ultimosPedidos',
            'topProductos',
            'categoriasMasVendidas'
        ));
    }
}
