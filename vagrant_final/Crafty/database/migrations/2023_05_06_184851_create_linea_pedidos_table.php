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
        Schema::create('linea_pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->foreign('pedido_id')->references('id')->on('pedidos')->cascadeOnDelete();
            $table->unsignedBigInteger('producto_id')->index();
            $table->foreign('producto_id')->references('id')->on('productos')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linea_pedidos');
    }
};
