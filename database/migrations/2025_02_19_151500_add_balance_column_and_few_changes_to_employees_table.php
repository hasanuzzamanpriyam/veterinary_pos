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
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('balance', 22, 2)->default(0)->after('others_amount');
            $table->decimal('salary_amount', 22, 2)->default(0)->change();
            $table->decimal('bonus_amount', 22, 2)->default(0)->change();
            $table->decimal('others_amount', 22, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};
