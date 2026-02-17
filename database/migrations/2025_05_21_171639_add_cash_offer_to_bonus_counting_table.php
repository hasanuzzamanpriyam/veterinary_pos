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
        Schema::table('supplier_bonuses', function (Blueprint $table) {
            $table->boolean('cash_offer')->default(false)->after('yearly');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_bonuses', function (Blueprint $table) {
            $table->dropColumn('cash_offer');
        });
    }
};
