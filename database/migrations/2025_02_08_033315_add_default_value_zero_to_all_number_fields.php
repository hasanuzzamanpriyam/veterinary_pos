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
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('purchase_rate', 22, 2)->default(0)->change();
            $table->decimal('price_rate', 22, 2)->default(0)->change();
            $table->decimal('mrp_rate', 22, 2)->default(0)->change();
        });

        Schema::table('customer_transaction_details', function (Blueprint $table) {
            $table->decimal('unit_price', 22, 2)->default(0)->change();
            $table->decimal('total_price', 22, 2)->default(0)->change();
        });

        Schema::table('customer_ledgers', function (Blueprint $table) {
            $table->decimal('balance', 22, 2)->default(0)->change();
            $table->decimal('vat', 22, 2)->default(0)->change();
            $table->decimal('carring', 22, 2)->default(0)->change();
            $table->decimal('price_discount', 22, 2)->default(0)->change();
            $table->decimal('total_price', 22, 2)->default(0)->change();
            $table->decimal('other_charge', 22, 2)->default(0)->change();
            $table->decimal('payment', 22, 2)->default(0)->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('amount', 22, 2)->default(0)->change();
            $table->decimal('payment_amount', 22, 2)->default(0)->change();
        });

        Schema::table('product_stores', function (Blueprint $table) {
            $table->decimal('purchase_price', 22, 2)->default(0)->change();
        });

        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->decimal('balance', 22, 2)->default(0)->change();
            $table->decimal('vat', 22, 2)->default(0)->change();
            $table->decimal('carring', 22, 2)->default(0)->change();
            $table->decimal('price_discount', 22, 2)->default(0)->change();
            $table->decimal('total_price', 22, 2)->default(0)->change();
            $table->decimal('other_charge', 22, 2)->default(0)->change();
            $table->decimal('payment', 22, 2)->default(0)->change();
        });

        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->decimal('unit_price', 22, 2)->default(0)->change();
            $table->decimal('total_price', 22, 2)->default(0)->change();
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->decimal('balance', 22, 2)->default(0)->change();
        });

        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->decimal('prev_balance', 22, 2)->default(0)->change();
            $table->decimal('amount', 22, 2)->default(0)->change();
        });

        Schema::table('banks', function (Blueprint $table) {
            $table->decimal('balance', 22, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_number_fields', function (Blueprint $table) {
            //
        });
    }
};
