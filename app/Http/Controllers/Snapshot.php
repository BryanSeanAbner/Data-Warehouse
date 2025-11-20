<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Snapshot extends Controller
{
    public function index(Request $request)
    {
        $productKey = $request->input('product_key');
        $storeKey = $request->input('store_key');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get products for dropdown
        $products = DB::table('product_dimension')
            ->select('product_key', 'product_description')
            ->orderBy('product_description')
            ->get();

        // Get stores for dropdown
        $stores = DB::table('store_dimension')
            ->select('store_key', 'store_name')
            ->orderBy('store_name')
            ->get();

        // Query untuk mengambil data number_of_turns berdasarkan tanggal
        $query = DB::table('inventory_periodic_fact as ipf')
            ->select([
                'ipf.date_key',
                'dd.date',
                DB::raw('AVG(ipf.number_of_turns) as avg_number_of_turns'),
                DB::raw('SUM(ipf.number_of_turns) as sum_number_of_turns')
            ])
            ->join('date_dimension as dd', 'ipf.date_key', '=', 'dd.date_key')
            ->whereNotNull('ipf.number_of_turns')
            ->where('ipf.number_of_turns', '>', 0)
            ->where('ipf.date_key', '!=', 0); // Exclude Not Applicable

        // Apply filters
        if (!empty($productKey)) {
            $query->where('ipf.product_key', $productKey);
        }

        if (!empty($storeKey)) {
            $query->where('ipf.store_key', $storeKey);
        }

        if (!empty($startDate)) {
            $startDateKey = str_replace('-', '', $startDate);
            $query->where('ipf.date_key', '>=', (int)$startDateKey);
        }

        if (!empty($endDate)) {
            $endDateKey = str_replace('-', '', $endDate);
            $query->where('ipf.date_key', '<=', (int)$endDateKey);
        }

        // Group by date and order by date
        $snapshotData = $query
            ->groupBy('ipf.date_key', 'dd.date')
            ->orderBy('ipf.date_key', 'asc')
            ->get();

        // Get selected product and store info for display
        $selectedProduct = null;
        if ($productKey) {
            $selectedProduct = DB::table('product_dimension')
                ->where('product_key', $productKey)
                ->first();
        }

        $selectedStore = null;
        if ($storeKey) {
            $selectedStore = DB::table('store_dimension')
                ->where('store_key', $storeKey)
                ->first();
        }

        return view('snapshot', [
            'snapshotData' => $snapshotData,
            'products' => $products,
            'stores' => $stores,
            'selectedProduct' => $selectedProduct,
            'selectedStore' => $selectedStore,
            'productKey' => $productKey,
            'storeKey' => $storeKey,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'current' => 'snapshot',
        ]);
    }
}
