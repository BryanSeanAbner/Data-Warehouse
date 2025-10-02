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
        Schema::create('cashier_dimension', function (Blueprint $table) {
            $table->increments('cashier_key');
            $table->string('cashier_name');
            $table->date('hire_date');
            $table->string('shift_type');
            $table->string('hire_source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashier_dimension');
    }
};