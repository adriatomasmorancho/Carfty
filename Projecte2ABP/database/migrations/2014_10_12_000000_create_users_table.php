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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->string('image')->nullable();
            $table->string('carrito')->default('[]');
            $table->boolean('shop')->default(0);
            $table->string('shop_name')->nullable();
            $table->string('shop_image')->nullable();
            $table->string('shop_banner')->default('#ffffff');
            $table->string('shop_url')->nullable();
            $table->boolean('verify_send')->default(0);
            $table->string('verification_code', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
