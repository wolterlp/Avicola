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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('egg_category_id'); // Relación con la tabla egg_categories
            $table->unsignedBigInteger('user_id'); // Relación con la tabla users
            $table->integer('quantity'); // Cantidad de huevos vendidos
            $table->decimal('price_per_unit', 8, 2); // Precio por unidad de huevo
            $table->decimal('total_price', 10, 2); // Precio total de la venta
            $table->timestamps();

            // Relaciones
            $table->foreign('egg_category_id')->references('id')->on('egg_categories')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_tables');
    }
};
