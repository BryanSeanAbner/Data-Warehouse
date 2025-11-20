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
        Schema::create('promotion_factless', function (Blueprint $table) {
            $table->unsignedInteger('product_key');
            $table->foreign('product_key')
                ->references('product_key')
                ->on('product_dimension')
                ->onDelete('cascade');

            $table->unsignedInteger('promotion_key');
            $table->foreign('promotion_key')
                ->references('promotion_key')
                ->on('promotion_dimension')
                ->onDelete('cascade');

            $table->primary(['product_key', 'promotion_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_factless');
    }
};
