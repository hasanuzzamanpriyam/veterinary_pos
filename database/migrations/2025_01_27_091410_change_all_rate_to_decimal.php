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
            UPDATE products
            SET
                purchase_rate = COALESCE(purchase_rate, 0),
                price_rate = COALESCE(price_rate, 0),
                mrp_rate = COALESCE(mrp_rate, 0)
        ");

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('selling_rate');
            $table->decimal('purchase_rate', 22, 2)->change();
            $table->decimal('price_rate', 22, 2)->change();
            $table->decimal('mrp_rate', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
