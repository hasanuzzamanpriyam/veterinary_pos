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
        Schema::table('customer_follow_updates', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('current_due');
            $table->dropColumn('paid_amount');
            $table->dropColumn('previous_advance');
            $table->dropColumn('current_advance');
            $table->dropColumn('payment_by');
            $table->dropColumn('bank_title');
            $table->dropColumn('deleted_at');
            $table->bigInteger('payment')->after('next_date')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_follow_updates', function (Blueprint $table) {
            //
        });
    }
};
