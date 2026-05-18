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
        Schema::create('products', function (Blueprint $table) {
            $table->increments('products_id');
            $table->string('name', 100);
            $table->unsignedInteger('categories_id')->nullable();
            $table->decimal('price', 12, 0);
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->string('unit', 20)->default('pcs');
            $table->string('barcode', 50)->nullable()->unique();
            $table->string('image_url', 255)->nullable();
            $table->timestamps();

            $table->foreign('categories_id')
                  ->references('categories_id')
                  ->on('categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
