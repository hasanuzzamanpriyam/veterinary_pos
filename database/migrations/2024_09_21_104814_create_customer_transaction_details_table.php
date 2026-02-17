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
        Schema::create('customer_transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('transaction_id');
            $table->integer('product_store_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('discount_qty')->nullable();
            $table->string('return_qty')->nullable();
            $table->string('weight')->nullable();
            $table->integer('unit_price')->nullable();
            $table->string('total_price')->nullable();
            $table->string('transaction_type')->enum('sale', 'return');
            $table->date('date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_transaction_details');
    }
};
