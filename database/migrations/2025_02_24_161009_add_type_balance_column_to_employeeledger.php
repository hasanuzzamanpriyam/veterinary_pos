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
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('voucher_no');
        });

        Schema::table('employee_ledgers', function (Blueprint $table) {
            $table->enum('type', ['salary', 'payment'])->after('employee_id');
            $table->decimal('amount', 22, 2)->default(0)->change();
            $table->decimal('balance', 22, 2)->default(0)->after('amount');
            $table->string('payment_method')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_ledgers', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('balance');
        });
    }
};
