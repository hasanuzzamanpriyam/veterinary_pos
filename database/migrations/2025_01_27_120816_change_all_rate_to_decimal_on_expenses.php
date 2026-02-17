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
            UPDATE expenses
            SET
                amount = COALESCE(amount, 0),
                payment_amount = COALESCE(payment_amount, 0)
        ");
        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('amount', 22, 2)->change();
            $table->decimal('payment_amount', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            //
        });
    }
};
