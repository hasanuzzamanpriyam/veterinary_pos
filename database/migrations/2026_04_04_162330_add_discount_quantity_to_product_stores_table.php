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
        if (!Schema::hasColumn('product_stores', 'discount_quantity')) {
            Schema::table('product_stores', function (Blueprint $table) {
                $table->integer('discount_quantity')->default(0)->after('product_quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_stores', function (Blueprint $table) {
            $table->dropColumn('discount_quantity');
        });
    }
};
