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
        Schema::create('customer_ledgers', function (Blueprint $table) {
            $table->id();

            $table->json('sales_invoices')->nullable(); 

            $table->unsignedBigInteger('customer_id');
            $table->integer('product_store_id')->nullable();

            $table->enum('type', ['sale', 'collection', 'return', 'other']);
            $table->string('payment_by')->nullable();
            $table->string('received_by')->nullable();
            $table->string('bank_title')->nullable();
            $table->string('delivery_man')->nullable();
            $table->string('transport_no')->nullable();

            $table->integer('total_qty')->nullable();
            $table->integer('product_discount')->nullable();
            $table->double('balance');
            $table->double('vat')->nullable();
            $table->double('carring')->nullable();
            $table->double('price_discount')->nullable();
            $table->double('total_price')->nullable();
            $table->double('other_charge')->nullable();
            $table->double('payment')->nullable();
            $table->string('remarks')->nullable();
            $table->date('date');
            $table->integer('u_id');
            $table->date('sale_date')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ledgers');
    }
};
