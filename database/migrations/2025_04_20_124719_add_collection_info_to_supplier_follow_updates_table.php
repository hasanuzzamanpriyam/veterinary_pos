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
        Schema::table('supplier_follow_updates', function (Blueprint $table) {
            $table->timestamp('payment_date')->nullable();
            $table->double('paid_amount')->nullable();
        });

        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->foreignId('supplier_follow_updates_id')->nullable()->default(null)->constrained('supplier_follow_updates', 'id')->after('product_store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_follow_updates', function (Blueprint $table) {
            $table->dropColumn('payment_date');
            $table->dropColumn('paid_amount');
        });

        Schema::table('supplier_ledgers', function (Blueprint $table) {
            $table->dropColumn('supplier_follow_updates_id');
        });
    }
};
