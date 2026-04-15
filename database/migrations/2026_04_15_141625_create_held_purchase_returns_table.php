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
        Schema::create('held_purchase_returns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('supplier_id')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('balance')->nullable();
            $table->string('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('purchase_date')->nullable();
            $table->string('return_date')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->string('product_store_id')->nullable();
            $table->string('product_store_name')->nullable();
            $table->string('purchase_invoice_no')->nullable();
            $table->string('delivery_man')->nullable();
            $table->text('remarks')->nullable();
            $table->longText('cart_data')->nullable(); // JSON data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('held_purchase_returns');
    }
};
