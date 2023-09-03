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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comprador_id')->index();
            $table->foreign('comprador_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unsignedBigInteger('vendedor_id')->index();
            $table->foreign('vendedor_id')->references('id')->on('users')->cascadeOnDelete();
            $table->text('productos')->default('[]'); 
            $table->integer('precio_pedido');
            $table->string('status'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
