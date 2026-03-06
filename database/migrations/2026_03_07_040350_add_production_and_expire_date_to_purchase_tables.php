<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->date('production_date')->nullable()->after('date');
            $table->date('expire_date')->nullable()->after('production_date');
        });

        Schema::table('product_stores', function (Blueprint $table) {
            $table->date('production_date')->nullable()->after('product_quantity');
            $table->date('expire_date')->nullable()->after('production_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_transaction_details', function (Blueprint $table) {
            $table->dropColumn(['production_date', 'expire_date']);
        });

        Schema::table('product_stores', function (Blueprint $table) {
            $table->dropColumn(['production_date', 'expire_date']);
        });
    }
};
