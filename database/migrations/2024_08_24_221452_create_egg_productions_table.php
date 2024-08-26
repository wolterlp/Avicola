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
        Schema::create('egg_productions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('quantity');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('egg_category_id')->nullable();
    
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('egg_category_id')->references('id')->on('egg_categories')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_productions');
    }
};
