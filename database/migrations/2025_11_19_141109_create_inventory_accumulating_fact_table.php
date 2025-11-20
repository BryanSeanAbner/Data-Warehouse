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
        Schema::create('inventory_accumulating_fact', function (Blueprint $table) {
            $table->id();
            $table->string('product_lot_receipt_number');
            
            $table->integer('date_received_key');
			$table->foreign('date_received_key')
				->references('date_key')
				->on('date_dimension')
				->onDelete('cascade');

            $table->integer('date_inspected_key');
			$table->foreign('date_inspected_key')
				->references('date_key')
				->on('date_dimension')
				->onDelete('cascade')
				->default(0);

            $table->integer('date_bin_placement_key');
			$table->foreign('date_bin_placement_key')
				->references('date_key')
				->on('date_dimension')
				->onDelete('cascade')
				->default(0);

            $table->integer('date_initial_shipment_key');
			$table->foreign('date_initial_shipment_key')
				->references('date_key')
				->on('date_dimension')
				->onDelete('cascade')
				->default(0);

            $table->integer('date_last_shipment_key');
			$table->foreign('date_last_shipment_key')
				->references('date_key')
				->on('date_dimension')
				->onDelete('cascade')
				->default(0);

            $table->unsignedInteger('product_key');
			$table->foreign('product_key')
				->references('product_key')
				->on('product_dimension')
				->onDelete('cascade');

			$table->unsignedInteger('warehouse_key');
			$table->foreign('warehouse_key')
				->references('warehouse_key')
				->on('warehouse_dimension')
				->onDelete('cascade');

			$table->unsignedInteger('vendor_key');
			$table->foreign('vendor_key')
				->references('vendor_key')
				->on('vendor_dimension')
				->onDelete('cascade');

            $table->unsignedInteger('quantity_received');
            $table->unsignedInteger('quantity_inspected')->nullable();
            $table->unsignedInteger('quantity_placed_in_bin')->nullable();
            $table->unsignedInteger('quantity_shipped_to_customer')->nullable();
            $table->unsignedInteger('receipt_to_inspected_lag')->nullable();
            $table->unsignedInteger('receipt_to_bin_placement_lag')->nullable();
            $table->unsignedInteger('receipt_to_initial_shipment_lag')->nullable();
            $table->unsignedInteger('initial_to_last_shipment_lag')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
		Schema::table('inventory_accumulating_fact', function (Blueprint $table) {
            $table->dropForeign(['date_received_key']);
			$table->dropForeign(['date_inspected_key']);
			$table->dropForeign(['date_bin_placement_key']);
			$table->dropForeign(['date_initial_shipment_key']);
			$table->dropForeign(['date_last_shipment_key']);
            $table->dropForeign(['product_key']);
            $table->dropForeign(['warehouse_key']);
            $table->dropForeign(['vendor_key']);
        });

        Schema::dropIfExists('inventory_accumulating_fact');
    }
};
