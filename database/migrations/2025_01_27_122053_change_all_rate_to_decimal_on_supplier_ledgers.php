<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            UPDATE supplier_ledgers
            SET
                balance = COALESCE(balance, 0),
                vat = COALESCE(vat, 0),
                carring = COALESCE(carring, 0),
                price_discount = COALESCE(price_discount, 0),
                total_price = COALESCE(total_price, 0),
                other_charge = COALESCE(other_charge, 0),
                payment = COALESCE(payment, 0)
        ");
        
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->decimal('balance', 22, 2)->change();
            $table->decimal('vat', 22, 2)->change();
            $table->decimal('carring', 22, 2)->change();
            $table->decimal('price_discount', 22, 2)->change();
            $table->decimal('total_price', 22, 2)->change();
            $table->decimal('other_charge', 22, 2)->change();
            $table->decimal('payment', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_ledgers', function (Blueprint $table) {
            //
        });
    }
};
