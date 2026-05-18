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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->increments('transaction_details_id');
            $table->unsignedInteger('transactions_id');
            $table->unsignedInteger('products_id');
            $table->integer('quantity');
            $table->decimal('price', 12, 0);
            $table->decimal('subtotal', 14, 0);

            $table->foreign('transactions_id')
                  ->references('transactions_id')
                  ->on('transactions')
                  ->onDelete('cascade');

            $table->foreign('products_id')
                  ->references('products_id')
                  ->on('products')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
