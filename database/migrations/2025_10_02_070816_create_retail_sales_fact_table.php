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
        Schema::create('retail_sales_fact', function (Blueprint $table) {
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

			$table->unsignedInteger('store_key');
			$table->foreign('store_key')
				->references('store_key')
				->on('store_dimension')
				->onDelete('cascade');

			$table->unsignedInteger('cashier_key');
			$table->foreign('cashier_key')
				->references('cashier_key')
				->on('cashier_dimension')
				->onDelete('cascade');

			$table->unsignedInteger('promotion_key');
			$table->foreign('promotion_key')
				->references('promotion_key')
				->on('promotion_dimension')
				->onDelete('cascade');

			$table->unsignedInteger('payment_method_key');
			$table->foreign('payment_method_key')
				->references('payment_method_key')
				->on('payment_method_dimension')
				->onDelete('cascade');
			
			$table->integer('transaction_id');
			$table->integer('sales_quantity');
			$table->decimal('regular_unit_price', 10, 2);
			$table->decimal('discount_unit_price', 10, 2);
			$table->decimal('net_unit_price', 10, 2);
			$table->decimal('extended_discount_amount', 10, 2);
			$table->decimal('extended_sales_amount', 10, 2);
			$table->decimal('extended_cost_amount', 10, 2);
			$table->decimal('extended_gross_profit_amount', 10, 2);
			$table->decimal('extended_gross_margin_amount', 10, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('retail_sales_fact', function (Blueprint $table) {
			$table->dropForeign(['date_key']);
			$table->dropForeign(['product_key']);
			$table->dropForeign(['store_key']);
			$table->dropForeign(['cashier_key']);
			$table->dropForeign(['promotion_key']);
			$table->dropForeign(['payment_method_key']);
		});

        Schema::dropIfExists('retail_sales_fact');
    }
};