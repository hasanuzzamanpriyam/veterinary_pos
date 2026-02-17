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
        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->dropColumn(['prev_balance', 'balance', 'u_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->integer('prev_balance')->nullable();
            $table->integer('balance')->nullable();
            $table->integer('u_id')->nullable();
            $table->date('date')->nullable();
        });
    }
};
