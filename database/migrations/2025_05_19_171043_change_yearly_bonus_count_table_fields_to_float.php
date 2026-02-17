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
        Schema::table('yearly_bonus_counts', function (Blueprint $table) {
            $table->float('start')->change();
            $table->float('end')->change();
            $table->float('rate')->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yearly_bonus_counts', function (Blueprint $table) {
            //
        });
    }
};
