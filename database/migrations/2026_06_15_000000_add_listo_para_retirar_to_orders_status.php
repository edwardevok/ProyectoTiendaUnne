<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL/MariaDB soportan ENUM nativo: ampliamos los valores permitidos.
        // SQLite (usado en la entrega) no soporta ENUM ni "MODIFY COLUMN", así que
        // dejamos la columna como string; la aplicación valida los estados válidos.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendiente','en_preparacion','listo_para_retirar','enviado','entregado') DEFAULT 'pendiente'");
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status')->default('pendiente')->change();
            });
        }
    }

    public function down(): void
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pendiente','en_preparacion','enviado','entregado') DEFAULT 'pendiente'");
        } else {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('status')->default('pendiente')->change();
            });
        }
    }
};
