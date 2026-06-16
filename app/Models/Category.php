<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // ========== RELACIONES ==========

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // ========== LÓGICA DE NEGOCIO ==========

    // Usa exists() en lugar de count() > 0 para una sola query eficiente.
    public function tieneProductos(): bool
    {
        return $this->products()->exists();
    }

    // Unidades vendidas por categoría, ordenadas de mayor a menor.
    public static function masVendidas(): \Illuminate\Support\Collection
    {
        return DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
            ->select('categories.name', DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_vendido'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_vendido')
            ->get();
    }
}
