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
        Schema::create('egg_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // Nombre de la categoría (B, A, AA, AAA, Jumbo)
            $table->text('description'); // Descripción de la categoría pesos en gramos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_categories');
    }

};
