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
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('transactions_id');
            $table->unsignedInteger('users_id');
            $table->decimal('total_amount', 14, 0)->default(0);
            $table->enum('payment_method', ['cash', 'debit', 'qris'])->default('cash');
            $table->decimal('amount_paid', 14, 0)->default(0);
            $table->decimal('change_amount', 14, 0)->default(0);
            $table->timestamp('transaction_date')->useCurrent();

            $table->foreign('users_id')
                  ->references('users_id')
                  ->on('users')
                  ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
