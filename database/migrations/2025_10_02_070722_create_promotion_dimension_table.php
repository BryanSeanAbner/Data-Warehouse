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
        Schema::create('promotion_dimension', function (Blueprint $table) {
            $table->increments('promotion_key');
            $table->string('promotion_code');
            $table->string('promotion_name');
            $table->string('promotion_media_type');
            $table->date('promotion_begin_date');
            $table->date('promotion_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_dimension');
    }
};
