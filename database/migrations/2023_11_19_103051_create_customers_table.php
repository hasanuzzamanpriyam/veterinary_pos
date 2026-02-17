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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('nid')->nullable();
            $table->date('birthday')->nullable();
            $table->string('ledger_page')->nullable();
            $table->string('type')->nullable();
            $table->integer('price_group_id')->nullable();
            $table->string('security')->nullable();
            $table->double('credit_limit')->nullable();
            $table->double('balance');
            $table->string('starting_date');
            $table->string('photo')->nullable();
            $table->string('guarantor_name')->nullable();
            $table->string('guarantor_company_name')->nullable();
            $table->date('guarantor_birthday')->nullable();
            $table->string('guarantor_mobile')->nullable();
            $table->string('guarantor_father_name')->nullable();
            $table->string('guarantor_phone')->nullable();
            $table->string('guarantor_email')->nullable();
            $table->string('guarantor_address')->nullable();
            $table->string('guarantor_security')->nullable();
            $table->string('guarantor_nid')->nullable();
            $table->string('guarantor_remarks')->nullable();
            $table->string('guarantor_photo')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
