<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('egg_inventory', function (Blueprint $table) {
            $table->id();  // Identificador único para cada registro
            $table->unsignedBigInteger('egg_category_id');  // ID de la categoría de huevos
            $table->integer('quantity');  // Cantidad de huevos (positiva para producción, negativa para ventas y ajustes)
            $table->string('transaction_type');  // Tipo de transacción: 'production', 'sale', 'adjustment', etc.
            $table->unsignedBigInteger('related_id')->nullable();  // ID relacionado con la transacción (producción o venta)
            $table->unsignedBigInteger('user_id')->nullable();  // ID del usuario que realizó la transacción
            $table->date('transaction_date');  // Fecha de la transacción
            $table->timestamps();  // Timestamps para 'created_at' y 'updated_at'

            // Claves foráneas
            $table->foreign('egg_category_id')->references('id')->on('egg_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_inventory');
    }
};
