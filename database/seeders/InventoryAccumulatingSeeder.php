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
        mt_srand(12345);
        
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

                if ($date->date_key < 20251206 and mt_rand(0, 5) === 0) {
                    for ($i = 0; $i < mt_rand(1, 4); $i++) {
                        $productKey = mt_rand(1, 50);
				        $vendorKey = mt_rand(1, 3);

                        $dateInspected = null;
                        $dateBinPlacement = null;
                        $dateInitialShipment = null;
                        $dateLastShipment = null;

                        $dateReceivedKey = $date->date_key;
                        $dateInspectedKey = 0;
                        $dateBinPlacementKey = 0;
                        $dateInitialShipmentKey = 0;
                        $dateLastShipmentKey = 0;

                        $quantityReceived = mt_rand(7, 15);
                        $quantityInspected = null;
                        $quantityPlacedInBin = null;
                        $quantityShippedToCustomer = null;

                        $receiptToInspectedLag = null;
                        $receiptToBinPlacementLag = null;
                        $receiptToInitialShipmentLag = null;
                        $initialToLastShipmentLag = null;
                        $receiptToLastShipmentLag = null;
                        
                        if ($date->date_key >= 20251116) {
                            $rng = mt_rand(0, 4);
                        } else {
                            $rng = 4;
                        }

                        if ($rng > 0) {
                            if (mt_rand(0, 18) === 0) {
                                $quantityInspected = $quantityReceived - 1;
                            } else {
                                $quantityInspected = $quantityReceived;
                            }
                            $receiptToInspectedLag = mt_rand(1, 3);
                            $dateInspected = Carbon::parse($date->date)->addDays($receiptToInspectedLag);
                            $dateInspectedKey = (int)$dateInspected->format('Ymd');
                        }

                        if ($rng > 1) {
                            if (mt_rand(0, 20) === 0) {
                                $quantityPlacedInBin = $quantityInspected - mt_rand(1, 2);
                            } else {
                                $quantityPlacedInBin = $quantityInspected;
                            }
                            $inspectedToBinPlacementLag = mt_rand(1, 2);
                            if ($warehouseKey % 2 == 1) {
                                $inspectedToBinPlacementLag += mt_rand(0, 2);
                            }
                            $receiptToBinPlacementLag = $receiptToInspectedLag + $inspectedToBinPlacementLag;
                            $dateBinPlacement = $dateInspected->addDays($inspectedToBinPlacementLag);
                            $dateBinPlacementKey = (int)$dateBinPlacement->format('Ymd');
                        }

                        if ($rng > 2) {
					        $quantityShippedToCustomer = $quantityPlacedInBin - mt_rand(1, 3);
					        $binPlacementToInitialShipmentLag = mt_rand(2, 6);
                            if ($warehouseKey % 2 == 0) {
                                $binPlacementToInitialShipmentLag += 1;
                            }
					        $receiptToInitialShipmentLag = $receiptToBinPlacementLag + $binPlacementToInitialShipmentLag;
                            $dateInitialShipment = $dateBinPlacement->addDays($binPlacementToInitialShipmentLag);
                            $dateInitialShipmentKey = (int)$dateInitialShipment->format('Ymd');
                        }

                        if ($rng > 3) {
                            $quantityShippedToCustomer = $quantityPlacedInBin;
                            $initialToLastShipmentLag = mt_rand(1, 4);
                            if ($warehouseKey % 2 == 0) {
                                $initialToLastShipmentLag += 3;
                            }
                            $dateLastShipment = $dateInitialShipment->addDays($initialToLastShipmentLag);
                            $dateLastShipmentKey = (int)$dateLastShipment->format('Ymd');

                            $receiptToLastShipmentLag = $receiptToInitialShipmentLag + $initialToLastShipmentLag;
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
                            'initial_to_last_shipment_lag' => $initialToLastShipmentLag,
                            'receipt_to_last_shipment_lag' => $receiptToLastShipmentLag,
                        ]);
                    }
                }
            }
        }
    }
}
