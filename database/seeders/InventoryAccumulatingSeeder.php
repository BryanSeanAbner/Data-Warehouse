<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryAccumulatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $receiptI = 163;
        $warehouses = DB::table('warehouse_dimension')->get();

        foreach ($warehouses as $warehouse) {
            $dates = DB::table('date_dimension')->get();
            $warehouseKey = $warehouse->warehouse_key;

            foreach($dates as $date) {
                if ($date->date_key === 0) {
                    continue;
                }

                $productLotReceiptNumber = "LR" . str($receiptI);
                $receiptI += 1;

                if ($date->date_key < 20251206 and random_int(0, 5) === 0) {
                    for ($i = 0; $i < random_int(1, 4); $i++) {
                        $productKey = random_int(1, 50);
				        $vendorKey = random_int(1, 3);

                        $dateInspected = null;
                        $dateBinPlacement = null;
                        $dateInitialShipment = null;
                        $dateLastShipment = null;

                        $dateReceivedKey = $date->date_key;
                        $dateInspectedKey = 0;
                        $dateBinPlacementKey = 0;
                        $dateInitialShipmentKey = 0;
                        $dateLastShipmentKey = 0;

                        $quantityReceived = random_int(7, 15);
                        $quantityInspected = null;
                        $quantityPlacedInBin = null;
                        $quantityShippedToCustomer = null;

                        $receiptToInspectedLag = null;
                        $receiptToBinPlacementLag = null;
                        $receiptToInitialShipmentLag = null;
                        $initialToLastShipmentLag = null;

                        $rng = random_int(0, 4);

                        if ($rng > 0) {
                            if (random_int(0, 18) === 0) {
                                $quantityInspected = $quantityReceived - 1;
                            } else {
                                $quantityInspected = $quantityReceived;
                            }
                            $receiptToInspectedLag = random_int(1, 3);
                            $dateInspected = Carbon::parse($date->date)->addDays($receiptToInspectedLag);
                            $dateInspectedKey = (int)$dateInspected->format('Ymd');
                        }

                        if ($rng > 1) {
                            if (random_int(0, 20) === 0) {
                                $quantityPlacedInBin = $quantityInspected - random_int(1, 2);
                            } else {
                                $quantityPlacedInBin = $quantityInspected;
                            }
                            $inspectedToBinPlacementLag = random_int(1, 2);
                            $receiptToBinPlacementLag = $receiptToInspectedLag + $inspectedToBinPlacementLag;
                            $dateBinPlacement = $dateInspected->addDays($inspectedToBinPlacementLag);
                            $dateBinPlacementKey = (int)$dateBinPlacement->format('Ymd');
                        }

                        if ($rng > 2) {
					        $quantityShippedToCustomer = $quantityPlacedInBin - random_int(1, 3);
					        $binPlacementToInitialShipmentLag = random_int(2, 6);
					        $receiptToInitialShipmentLag = $receiptToBinPlacementLag + $binPlacementToInitialShipmentLag;
                            $dateInitialShipment = $dateBinPlacement->addDays($binPlacementToInitialShipmentLag);
                            $dateInitialShipmentKey = (int)$dateInitialShipment->format('Ymd');
                        }

                        if ($rng > 3) {
                            $quantityShippedToCustomer = $quantityPlacedInBin;
                            $initialToLastShipmentLag = random_int(1, 4);
                            $dateLastShipment = $dateInitialShipment->addDays($initialToLastShipmentLag);
                            $dateLastShipmentKey = (int)$dateLastShipment->format('Ymd');
                        }

                        DB::table('inventory_accumulating_fact')->insert([
                            'product_lot_receipt_number' => $productLotReceiptNumber,
                            'date_received_key' => $dateReceivedKey,
                            'date_inspected_key' => $dateInspectedKey,
                            'date_bin_placement_key' => $dateBinPlacementKey,
                            'date_initial_shipment_key' => $dateInitialShipmentKey,
                            'date_last_shipment_key' => $dateLastShipmentKey,
                            'product_key' => $productKey,
                            'warehouse_key' => $warehouseKey,
                            'vendor_key' => $vendorKey,
                            'quantity_received' => $quantityReceived,
                            'quantity_inspected' => $quantityInspected,
                            'quantity_placed_in_bin' => $quantityPlacedInBin,
                            'quantity_shipped_to_customer' => $quantityShippedToCustomer,
                            'receipt_to_inspected_lag' => $receiptToInspectedLag,
                            'receipt_to_bin_placement_lag' => $receiptToBinPlacementLag,
                            'receipt_to_initial_shipment_lag' => $receiptToInitialShipmentLag,
                            'initial_to_last_shipment_lag' => $initialToLastShipmentLag
                        ]);
                    }
                }
            }
        }
    }
}
