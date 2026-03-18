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
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->decimal('single_discount', 15, 2)->nullable()->after('total_price');
            $table->decimal('total_discount', 15, 2)->nullable()->after('single_discount');
            $table->decimal('net_amount', 15, 2)->nullable()->after('total_discount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->dropColumn(['single_discount', 'total_discount', 'net_amount']);
        });
    }
};
