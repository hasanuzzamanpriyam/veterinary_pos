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
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('receiver_name');
            $table->dropColumn('receiving_by');
            $table->dropColumn('gary_number');
            $table->dropColumn('driver_name');
            $table->dropColumn('load_point');
            $table->dropColumn('delivery_point');
            $table->dropColumn('others');

            // rename column name
            $table->renameColumn('id_no', 'employee_id');

            // add new columns
            $table->bigInteger('expense_category')->unsigned()->after('id')->nullable();
            $table->string('paying_by')->after('purpose')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn('expense_category');
            $table->dropColumn('paying_by');
        });
    }
};
