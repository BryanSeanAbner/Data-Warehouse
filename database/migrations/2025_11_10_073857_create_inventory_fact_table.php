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
        Schema::create('inventory_fact', function (Blueprint $table) {
            $table->id();
            
            $table->integer('date_key');
            $table->foreign('date_key')
                ->references('date_key')
                ->on('date_dimension')
                ->onDelete('cascade');

            $table->unsignedInteger('product_key');
            $table->foreign('product_key')
                ->references('product_key')
                ->on('product_dimension')
                ->onDelete('cascade');

            $table->integer('quantity_of_sold')->default(0);
            $table->integer('quantity_of_hand')->default(0);
            $table->integer('final_quantity_on_hand')->default(0);
            $table->integer('avg_quantity_sold')->default(0);
            
            $table->decimal('number_of_turns', 15, 4)->nullable()->comment('quantity_of_sold / quantity_of_hand');
            $table->decimal('number_of_day_supply', 15, 4)->nullable()->comment('final_quantity_on_hand / avg_quantity_sold');
            
            $table->decimal('extended_value_of_inventory_cost', 15, 2)->default(0);
            $table->decimal('latest_selling_price', 15, 2)->default(0);
            
            $table->index(['date_key', 'product_key']);
            $table->index('date_key');
            $table->index('product_key');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_fact', function (Blueprint $table) {
            $table->dropForeign(['date_key']);
            $table->dropForeign(['product_key']);
        });

        Schema::dropIfExists('inventory_fact');
    }
};
