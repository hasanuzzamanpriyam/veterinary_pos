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
        Schema::table('held_purchases', function (Blueprint $table) {
            $table->string('purchase_date')->nullable();
            $table->string('supplier_remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('held_purchases', function (Blueprint $table) {
            $table->dropColumn('purchase_date');
            $table->dropColumn('supplier_remarks');
        });
    }
};
