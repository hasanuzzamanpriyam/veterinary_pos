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
            $table->decimal('balance', 22, 2)->after('amount')->default(0);
            $table->unsignedBigInteger('u_id')->after('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_transaction', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->dropColumn('u_id');
        });
    }
};
