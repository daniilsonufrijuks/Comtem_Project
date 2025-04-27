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
        Schema::create('poincluded', function (Blueprint $table) {
            $table->foreignId('order_id')->constrained()->onDelete('cascade');  // Assumes an 'orders' table exists
            $table->foreignId('product_id')->constrained()->onDelete('cascade');  // Assumes a 'products' table exists
            $table->integer('quantity')->default(1);  // Store quantity of product in the order
            $table->decimal('price', 8, 2);  // Store the price of the product at the time of order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poincluded');
    }
};
