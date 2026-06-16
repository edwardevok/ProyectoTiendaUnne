<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'delivery_type',
        'address',
        'status',
        'dispatched_at',
    ];

    // ========== RELACIONES ==========

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // ========== SCOPES ==========

    public function scopePendientes($query)
    {
        return $query->whereIn('status', ['pendiente', 'en_preparacion', 'listo_para_retirar']);
    }

    public function scopeEntregados($query)
    {
        return $query->where('status', 'entregado');
    }

    // ========== LÓGICA DE NEGOCIO ==========

    // Últimos N pedidos con su usuario cargado.
    public static function ultimos(int $limite = 5)
    {
        return self::with('user')->orderBy('created_at', 'desc')->take($limite)->get();
    }

    // Construye la query de pedidos activos aplicando búsqueda y filtros para el panel admin.
    public static function filtrados(?string $busqueda, ?string $estado, ?string $ordenTotal)
    {
        return self::with(['user', 'items.product'])
            ->where('status', '!=', 'entregado')
            ->when($busqueda, function ($q) use ($busqueda) {
                $id = (int) str_replace('#', '', $busqueda);
                if ($id > 0) {
                    $q->where('id', $id);
                }
            })
            ->when(
                $estado && $estado !== 'Todos los estados',
                fn($q) => $q->where('status', str_replace(' ', '_', strtolower($estado)))
            )
            ->when(
                $ordenTotal,
                fn($q) => $q->orderBy('total', $ordenTotal),
                fn($q) => $q->orderBy('created_at', 'desc')
            );
    }

    // Crea una orden desde el carrito de sesión dentro de una transacción DB.
    // Los precios se leen de la BD (no de la sesión) para evitar price tampering.
    public static function crearDesdeCarrito(array $cart, string $deliveryType, ?string $address, int $userId): self
    {
        return DB::transaction(function () use ($cart, $deliveryType, $address, $userId) {
            $total = 0;
            $itemsValidados = [];

            foreach ($cart as $id => $details) {
                $producto = Product::lockForUpdate()->find($id);

                if (!$producto) {
                    throw new \Exception("Lo sentimos, el producto '{$details['name']}' fue retirado del catálogo justo antes de tu pago. Revisa tu carrito.");
                }

                if ($producto->stock < $details['quantity']) {
                    throw new \Exception("Lo sentimos, nos quedamos sin stock de {$producto->name} mientras procesabas el pago.");
                }

                $total += $producto->price * $details['quantity'];
                $itemsValidados[] = [
                    'producto' => $producto,
                    'quantity' => $details['quantity'],
                    'price'    => $producto->price,
                ];
            }

            $direccionFinal = $deliveryType === 'domicilio' ? $address : 'campusUNNE';

            $orden = self::create([
                'user_id'       => $userId,
                'total'         => $total,
                'delivery_type' => $deliveryType,
                'address'       => $direccionFinal,
                'status'        => 'pendiente',
            ]);

            foreach ($itemsValidados as $item) {
                $orden->items()->create([
                    'product_id' => $item['producto']->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                ]);
                $item['producto']->decrement('stock', $item['quantity']);
            }

            return $orden;
        });
    }

    // Actualiza el estado y registra la fecha de despacho cuando corresponde.
    public function actualizarEstado(string $nuevoEstado): void
    {
        $this->status = $nuevoEstado;

        if (in_array($nuevoEstado, ['enviado', 'entregado']) && !$this->dispatched_at) {
            $this->dispatched_at = now();
        }

        $this->save();
    }
}
