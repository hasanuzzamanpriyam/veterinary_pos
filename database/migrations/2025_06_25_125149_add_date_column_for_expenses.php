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
        Schema::table('expenses', function (Blueprint $table) {
            $table->date('date')->nullable()->after('other_charge');
        });
        Schema::table('employee_ledgers', function (Blueprint $table) {
            $table->date('date')->nullable()->after('amount');
        });
        DB::table('expenses')->update(['date' => DB::raw('DATE(created_at)')]);
        Schema::table('expenses', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
        });
        DB::table('employee_ledgers')->update(['date' => DB::raw('DATE(created_at)')]);
        Schema::table('employee_ledgers', function (Blueprint $table) {
            $table->date('date')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('employee_ledgers', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
};
