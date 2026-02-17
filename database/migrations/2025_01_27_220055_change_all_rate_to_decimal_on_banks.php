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
        Schema::table('banks', function (Blueprint $table) {
            // need to change the column name first
            $table->renameColumn('opening_balance', 'balance');
        });
        DB::statement("
            UPDATE banks
            SET
                balance = COALESCE(balance, 0)
        ");
        Schema::table('banks', function (Blueprint $table) {
            // need to change the column name first
            $table->decimal('balance', 22, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            //
        });
    }
};
