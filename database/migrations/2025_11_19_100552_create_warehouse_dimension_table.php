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
        Schema::create('warehouse_dimension', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_key')->primary();
            $table->unsignedInteger('warehouse_number');
            $table->string('warehouse_name');
            $table->string('warehouse_address');
            $table->string('warehouse_city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_dimension');
    }
};
