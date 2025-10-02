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
        Schema::create('date_dimension', function (Blueprint $table) {
        	$table->integer('date_key');
			$table->primary('date_key');
			$table->date('date');
			$table->string('full_date_description');
			$table->string('day_of_week');
        	$table->integer('day_number_in_calendar_month');
        	$table->integer('day_number_in_calendar_year');
        	$table->integer('day_number_in_fiscal_month');
        	$table->integer('day_number_in_fiscal_year');
			$table->string('calendar_month_name');
        	$table->integer('calendar_month_number_in_year');
        	$table->integer('calendar_year_month');
			$table->string('calendar_quarter');
			$table->string('calendar_year_quarter');
			$table->integer('calendar_year');
			$table->string('fiscal_month');
        	$table->integer('fiscal_month_number_in_year');
			$table->integer('fiscal_year_month');
			$table->string('fiscal_quarter');
			$table->string('fiscal_year_quarter');
			$table->integer('fiscal_year');
			$table->string('holiday_indicator');
			$table->string('weekday_indicator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_dimension');
    }
};