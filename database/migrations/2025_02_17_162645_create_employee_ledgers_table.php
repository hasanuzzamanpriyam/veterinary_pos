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
        Schema::create('employee_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("employee_id");
            $table->string('payment_method');
            $table->string('remarks')->nullable();
            $table->decimal('amount', 22, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_ledgers');
    }
};
