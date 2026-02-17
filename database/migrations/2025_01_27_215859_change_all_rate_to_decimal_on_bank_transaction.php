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
            UPDATE bank_transaction
            SET
                prev_balance = COALESCE(prev_balance, 0),
                amount = COALESCE(amount, 0)
        ");

        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->decimal('prev_balance', 22, 2)->change();
            $table->decimal('amount', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transaction', function (Blueprint $table) {
            //
        });
    }
};
