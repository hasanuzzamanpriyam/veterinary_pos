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
        Schema::create('bank_transaction', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id');
            $table->enum('type',['deposit','withdraw']);
            $table->string('bank_name');
            $table->string('bank_branch_name');
            $table->string('bank_account_no');
            $table->integer('prev_balance');
            $table->integer('amount');
            $table->date('date');
            $table->string('payment_by');
            $table->string('payment_by_bank')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transaction');
    }
};
