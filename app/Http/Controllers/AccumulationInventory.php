<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccumulationInventory extends Controller
{
    public function index(Request $request)
    {
        $type = $request->input('type', 'received'); // default: received
        $perPage = $request->input('per_page', 25);

        // Mendapatkan tanggal pertama dan terakhir tahun 2025
        $firstDate2025 = Carbon::create(2025, 1, 1)->format('Ymd');
        $lastDate2025 = Carbon::create(2025, 12, 31)->format('Ymd');

        // Query untuk rata-rata receipt to initial shipment lag tahun 2025 per warehouse
        $avgReceiptToInitialLag = DB::table('inventory_accumulating_fact')
            ->select([
                'inventory_accumulating_fact.warehouse_key',
                'warehouse_dimension.warehouse_name',
                DB::raw('AVG(inventory_accumulating_fact.receipt_to_initial_shipment_lag) as avg_lag')
            ])
            ->join('warehouse_dimension', 'inventory_accumulating_fact.warehouse_key', '=', 'warehouse_dimension.warehouse_key')
            ->where('inventory_accumulating_fact.date_initial_shipment_key', '>=', (int)$firstDate2025)
            ->where('inventory_accumulating_fact.date_initial_shipment_key', '<=', (int)$lastDate2025)
            ->whereNotNull('inventory_accumulating_fact.receipt_to_initial_shipment_lag')
            ->where('inventory_accumulating_fact.receipt_to_initial_shipment_lag', '>', 0)
            ->where('inventory_accumulating_fact.warehouse_key', '!=', 0)
            ->where('warehouse_dimension.warehouse_name', '!=', 'Not Applicable')
            ->groupBy('inventory_accumulating_fact.warehouse_key', 'warehouse_dimension.warehouse_name')
            ->orderBy('warehouse_dimension.warehouse_name')
            ->get();

        // Query berdasarkan type button
        $query = DB::table('inventory_accumulating_fact')
            ->select([
                'inventory_accumulating_fact.id',
                'product_dimension.product_description',
                'warehouse_dimension.warehouse_name'
            ])
            ->join('product_dimension', 'inventory_accumulating_fact.product_key', '=', 'product_dimension.product_key')
            ->join('warehouse_dimension', 'inventory_accumulating_fact.warehouse_key', '=', 'warehouse_dimension.warehouse_key');

        // Menambahkan kolom berdasarkan type
        switch ($type) {
            case 'received':
                $query->addSelect([
                    'inventory_accumulating_fact.date_received_key as date_key',
                    'inventory_accumulating_fact.quantity_received as quantity',
                    'inventory_accumulating_fact.receipt_to_inspected_lag as receipt_lag'
                ])
                ->where('inventory_accumulating_fact.date_received_key', '!=', 0)
                ->where(function($q) {
                    $q->where('inventory_accumulating_fact.date_inspected_key', '=', 0)
                      ->orWhereNull('inventory_accumulating_fact.date_inspected_key');
                })
                ->orderBy('inventory_accumulating_fact.date_received_key', 'desc');
                break;

            case 'inspected':
                $query->addSelect([
                    'inventory_accumulating_fact.date_inspected_key as date_key',
                    'inventory_accumulating_fact.quantity_inspected as quantity',
                    'inventory_accumulating_fact.receipt_to_inspected_lag as receipt_lag'
                ])
                ->where('inventory_accumulating_fact.date_inspected_key', '!=', 0)
                ->where(function($q) {
                    $q->where('inventory_accumulating_fact.date_bin_placement_key', '=', 0)
                      ->orWhereNull('inventory_accumulating_fact.date_bin_placement_key');
                })
                ->orderBy('inventory_accumulating_fact.date_inspected_key', 'desc');
                break;

            case 'bin':
                $query->addSelect([
                    'inventory_accumulating_fact.date_bin_placement_key as date_key',
                    'inventory_accumulating_fact.quantity_placed_in_bin as quantity',
                    'inventory_accumulating_fact.receipt_to_bin_placement_lag as receipt_lag'
                ])
                ->where('inventory_accumulating_fact.date_bin_placement_key', '!=', 0)
                ->where(function($q) {
                    $q->where('inventory_accumulating_fact.date_initial_shipment_key', '=', 0)
                      ->orWhereNull('inventory_accumulating_fact.date_initial_shipment_key');
                })
                ->orderBy('inventory_accumulating_fact.date_bin_placement_key', 'desc');
                break;

            case 'first_shipment':
                $query->addSelect([
                    'inventory_accumulating_fact.date_initial_shipment_key as date_key',
                    'inventory_accumulating_fact.quantity_shipped_to_customer as quantity',
                    'inventory_accumulating_fact.receipt_to_initial_shipment_lag as receipt_lag'
                ])
                ->where('inventory_accumulating_fact.date_initial_shipment_key', '!=', 0)
                ->where(function($q) {
                    $q->where('inventory_accumulating_fact.date_last_shipment_key', '=', 0)
                      ->orWhereNull('inventory_accumulating_fact.date_last_shipment_key');
                })
                ->orderBy('inventory_accumulating_fact.date_initial_shipment_key', 'desc');
                break;

            case 'last_shipment':
                $query->addSelect([
                    'inventory_accumulating_fact.date_last_shipment_key as date_key',
                    'inventory_accumulating_fact.quantity_shipped_to_customer as quantity',
                    'inventory_accumulating_fact.receipt_to_last_shipment_lag as receipt_lag'
                ])
                ->where('inventory_accumulating_fact.date_last_shipment_key', '!=', 0)
                ->orderBy('inventory_accumulating_fact.date_last_shipment_key', 'desc');
                break;

            default:
                $query->addSelect([
                    'inventory_accumulating_fact.date_received_key as date_key',
                    'inventory_accumulating_fact.quantity_received as quantity',
                    'inventory_accumulating_fact.receipt_to_inspected_lag as receipt_lag'
                ])
                ->where('inventory_accumulating_fact.date_received_key', '!=', 0)
                ->orderBy('inventory_accumulating_fact.date_received_key', 'desc');
        }

        $inventories = $query->paginate($perPage)->withQueryString();

        return view('accumulation_inventory', [
            'inventories' => $inventories,
            'type' => $type,
            'perPage' => $perPage,
            'avgReceiptToInitialLag' => $avgReceiptToInitialLag,
        ]);
    }
}
