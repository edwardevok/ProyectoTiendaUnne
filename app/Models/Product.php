<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'image',
        'is_active',
    ];

    // ========== RELACIONES ==========

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ========== SCOPES ==========

    public function scopeActivos($query)
    {
        return $query->where('is_active', 1);
    }

    // ========== LÓGICA DE NEGOCIO ==========

    public function desactivar(): void
    {
        $this->is_active = 0;
        $this->save();
        $this->delete();
    }

    public function restaurar(): void
    {
        $this->restore();
        $this->is_active = 1;
        $this->save();
    }

    // Filtra el carrito eliminando productos que ya no existen o están inactivos.
    // Devuelve el carrito limpio sin los productos removidos.
    public static function filtrarCarrito(array $cart): array
    {
        if (empty($cart)) {
            return $cart;
        }

        $idsActivos = self::whereIn('id', array_keys($cart))
            ->where('is_active', 1)
            ->pluck('id')
            ->toArray();

        return array_filter($cart, fn($id) => in_array($id, $idsActivos), ARRAY_FILTER_USE_KEY);
    }

    // Top N productos más vendidos según la suma de unidades en order_items.
    // $soloConStock=true para el carrusel del index (excluye agotados).
    // $soloConStock=false para el dashboard (muestra vendidos aunque estén sin stock).
    public static function masVendidos(int $limite = 5, bool $soloConStock = true)
    {
        $query = self::withSum('items', 'quantity')
            ->activos()
            ->orderByDesc('items_sum_quantity')
            ->take($limite);

        if ($soloConStock) {
            $query->where('stock', '>', 0);
        }

        return $query->get();
    }
}
