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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('orderId');
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('productId');
            $table->integer('quantity');
            $table->date('orderDate');
            $table->decimal('price', 8, 2);
            $table->string('status');
            $table->timestamps();

            $table->foreign('userId')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('productId')->references('productId')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
