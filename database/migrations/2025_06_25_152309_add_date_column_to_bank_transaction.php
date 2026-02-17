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
        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->date('date')->nullable()->after('remarks');
        });
        DB::table('bank_transaction')->update(['date' => DB::raw('DATE(created_at)')]);
        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
