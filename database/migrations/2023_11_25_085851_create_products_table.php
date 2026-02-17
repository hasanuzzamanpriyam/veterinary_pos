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
        Schema::create('products', function (Blueprint $table){
            $table->id();
            $table->string('code')->nullable();
            $table->string('name');
            $table->integer('brand_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('size_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('metric_ton')->nullable();
            $table->string('barcode')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('purchase_rate')->nullable();
            $table->integer('price_rate')->nullable();
            $table->integer('selling_rate')->nullable();
            $table->integer('mrp_rate')->nullable();
            $table->string('alert_quantity')->nullable();
            $table->string('warehouse_id')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->string('remarks')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');
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
