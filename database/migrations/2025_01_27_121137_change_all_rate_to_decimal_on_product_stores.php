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
            UPDATE product_stores
            SET
                purchase_price = COALESCE(purchase_price, 0)
        ");
        Schema::table('product_stores', function (Blueprint $table) {
            $table->decimal('purchase_price', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_stores', function (Blueprint $table) {
            //
        });
    }
};
