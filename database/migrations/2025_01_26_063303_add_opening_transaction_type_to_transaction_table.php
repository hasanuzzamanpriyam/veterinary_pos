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
        DB::statement("ALTER TABLE bank_transaction MODIFY COLUMN type ENUM('withdraw', 'deposit', 'opening', 'others') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE bank_transaction MODIFY COLUMN type ENUM('withdraw', 'deposit') NOT NULL");
    }
};
