<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Acá también lo corregimos

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    // Relación: Este detalle pertenece a un pedido
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: Este detalle corresponde a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}