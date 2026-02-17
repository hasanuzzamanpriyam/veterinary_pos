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
        Schema::dropIfExists('stacks');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('stacks', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('subcategory_id')->nullable();
            $table->integer('brand_id')->nullable();
            $table->integer('total_purchase')->nullable()->comment('Total Purchase Quantity');
            $table->integer('purchase_return')->nullable()->comment('Total Return Quantity');
            $table->integer('total_salse')->nullable()->comment('Total Salse Quantity');
            $table->integer('salse_return')->nullable()->comment('Total Return Quantity');
            $table->integer('stack')->nullable()->comment('Present Available Quantity');
            $table->double('metric_ton')->nullable()->comment('Total Metric Ton Calculation');
            $table->integer('purchase_value')->nullable()->comment('Total Purchase Cost');
            $table->integer('salse_value')->nullable()->comment('Total Selling Price');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
