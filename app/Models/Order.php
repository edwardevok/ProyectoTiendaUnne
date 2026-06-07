<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- Acá estaba el detalle

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'delivery_type',
        'address',
        'status',
        'dispatched_at'
    ];

    // Relación: Un pedido pertenece a un usuario (cliente)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: Un pedido tiene muchos productos detallados
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}