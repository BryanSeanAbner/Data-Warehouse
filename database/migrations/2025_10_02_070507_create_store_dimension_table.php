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
        Schema::create('store_dimension', function (Blueprint $table) {
            $table->increments('store_key');
            $table->string('store_name');
            $table->string('store_street_address');
            $table->string('store_subdistrict');
            $table->string('store_district');
            $table->string('store_city');
            $table->string('store_province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_dimension');
    }
};