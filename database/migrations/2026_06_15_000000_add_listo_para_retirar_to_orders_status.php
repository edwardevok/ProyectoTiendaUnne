<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendiente','en_preparacion','listo_para_retirar','enviado','entregado') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendiente','en_preparacion','enviado','entregado') DEFAULT 'pendiente'");
    }
};
