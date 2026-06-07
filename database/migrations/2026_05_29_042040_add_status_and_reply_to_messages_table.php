<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            
            // Verificamos si la columna 'status' NO existe antes de crearla
            if (!Schema::hasColumn('messages', 'status')) {
                $table->string('status')->default('No leído');
            }

            // Verificamos si la columna 'reply' NO existe antes de crearla
            if (!Schema::hasColumn('messages', 'reply')) {
                $table->text('reply')->nullable();
            }
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            //
        });
    }
};
