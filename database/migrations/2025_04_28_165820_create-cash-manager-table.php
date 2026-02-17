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
        Schema::create('cash_managers', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date'); // <-- Use dateTime instead of date
            $table->decimal('collection', 15, 2)->default(0);
            $table->decimal('payment', 15, 2)->default(0);
            $table->decimal('expense', 15, 2)->default(0);
            $table->decimal('home_cash', 15, 2)->default(0);
            $table->decimal('short_cash', 15, 2)->default(0);
            $table->decimal('dokan_cash', 15, 2)->default(0);
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_managers');
    }
};
