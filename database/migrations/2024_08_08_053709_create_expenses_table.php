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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->unsignedBigInteger('voucher_no');
            $table->string('expense_type')->nullable();
            $table->unsignedBigInteger('id_no')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('purpose', 255)->nullable();
            $table->integer('amount')->nullable();
            $table->string('amount_month')->nullable();
            $table->string('year', 10)->nullable();
            $table->string('other_charge')->nullable();
            $table->string('payment_by')->nullable();
            $table->string('receiving_by')->nullable();
            $table->integer('payment_amount')->nullable();
            $table->string('remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->string('gary_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('load_point', 255)->nullable();
            $table->string('delivery_point', 255)->nullable();
            $table->string('others')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
