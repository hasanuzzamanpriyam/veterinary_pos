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
            if (!Schema::hasColumn('supplier_transaction_details', 'value')) {
                $table->decimal('value', 15, 2)->nullable()->after('unit_price');
            }
            if (!Schema::hasColumn('supplier_transaction_details', 'discount')) {
                $table->decimal('discount', 15, 2)->nullable()->after('value');
            }
            if (!Schema::hasColumn('supplier_transaction_details', 'vat')) {
                $table->decimal('vat', 15, 2)->nullable()->after('discount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->dropColumn(['value', 'discount', 'vat']);
        });
    }
};
