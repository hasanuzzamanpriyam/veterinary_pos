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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('nid')->nullable();
            $table->date('birthday')->nullable();
            $table->string('joining_date')->nullable();
            $table->string('designation')->nullable();
            $table->integer('salary_amount')->nullable();
            $table->integer('bonus_amount')->nullable();
            $table->string('security')->nullable();
            $table->integer('others_amount')->nullable();
            $table->string('remarks')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
