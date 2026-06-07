<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // El ID del pedido (ej: #1023)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Quién lo compró
            $table->decimal('total', 10, 2); // Monto total de la venta
            
            // Forma de retiro y dirección condicional
            $table->enum('delivery_type', ['campus', 'domicilio'])->default('campus');
            $table->string('address')->default('campusUNNE'); 
            
            // Estado del pedido (Agregamos 'en_preparacion' como sugeriste)
            $table->enum('status', ['pendiente', 'en_preparacion', 'enviado', 'entregado'])->default('pendiente');
            
            // Fechas de control
            $table->timestamp('dispatched_at')->nullable(); // Cuándo se despachó (Enviado)
            $table->timestamps(); // Genera created_at (Fecha del pedido) y updated_at automáticamente
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};