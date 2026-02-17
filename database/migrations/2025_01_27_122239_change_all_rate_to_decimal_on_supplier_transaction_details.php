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
            UPDATE supplier_transaction_details
            SET
                unit_price = COALESCE(unit_price, 0),
                total_price = COALESCE(total_price, 0)
        ");
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->decimal('unit_price', 22, 2)->change();
            $table->decimal('total_price', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            //
        });
    }
};
