<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $grossProductKey = $request->input('gross_product_key');
        $grossStartDate  = $request->input('gross_start_date');
        $grossEndDate    = $request->input('gross_end_date');

        // STATISTIK UTAMA (dengan filter gross)
        $query = DB::table('retail_sales_fact as sf');

        // Terapkan filter yang sama seperti di chart gross
        if (!empty($grossProductKey)) {
            $query->where('sf.product_key', $grossProductKey);
        }

        if (!empty($grossStartDate)) {
            // Konversi YYYY-MM-DD ke YYYYMMDD
            $startDate = str_replace('-', '', $grossStartDate);
            $query->where('sf.date_key', '>=', $startDate);
        }

        if (!empty($grossEndDate)) {
            // Konversi YYYY-MM-DD ke YYYYMMDD
            $endDate = str_replace('-', '', $grossEndDate);
            $query->where('sf.date_key', '<=', $endDate);
        }

        $totalRecords     = $query->count();
        $totalGrossProfit = $query->sum('extended_gross_profit_amount');
        $avgGrossProfit   = $query->avg('extended_gross_profit_amount');
        $maxGrossProfit   = $query->max('extended_gross_profit_amount');
        $minGrossProfit   = $query->min('extended_gross_profit_amount');

        // DROPDOWN: PRODUK
        $products = DB::table('product_dimension')
            ->select('product_key', 'product_description')
            ->orderBy('product_description')
            ->get();

        // DROPDOWN: TANGGAL (YYYYMMDD)
        $dates = DB::table('date_dimension')
            ->select('date_key')
            ->orderBy('date_key', 'desc')
            ->get();

        // DROPDOWN: STORES
        $stores = DB::table('store_dimension')
            ->select('store_key', 'store_name')
            ->orderBy('store_name')
            ->get();

        // DATA UNTUK CHART
        $chartData = $this->getChartData($request);

        // DATA - QUANTITY AWAL HARI
        $chartAwal = $this->getQuantityAwalHari($request);

        // DATA - QUANTITY AKHIR HARI
        $chartAkhir = $this->getQuantityAkhirHari($request);

        return view('dashboard', compact(
            'totalRecords',
            'totalGrossProfit',
            'avgGrossProfit',
            'maxGrossProfit',
            'minGrossProfit',
            'products',
            'dates',
            'stores',
            'chartData',
            'chartAwal',
            'chartAkhir'
        ));
    }

    // CHART DATA
    public function getChartData(Request $request)
    {
        $query = DB::table('retail_sales_fact as sf')
            ->join('store_dimension as s', 'sf.store_key', '=', 's.store_key')
            ->join('product_dimension as p', 'sf.product_key', '=', 'p.product_key')
            ->join('date_dimension as d', 'sf.date_key', '=', 'd.date_key');

        $productKey = $request->input('gross_product_key');
        $startDateParam = $request->input('gross_start_date');
        $endDateParam = $request->input('gross_end_date');

        // FILTER PRODUK
        if (!empty($productKey)) {
            $query->where('sf.product_key', $productKey);
        }

        // FILTER RANGE TANGGAL
        // Konversi format YYYY-MM-DD ke YYYYMMDD
        if (!empty($startDateParam)) {
            $startDate = str_replace('-', '', $startDateParam);
            $query->where('sf.date_key', '>=', $startDate);
        }

        if (!empty($endDateParam)) {
            $endDate = str_replace('-', '', $endDateParam);
            $query->where('sf.date_key', '<=', $endDate);
        }

        // AMBIL DATA UNTUK CHART
        $data = $query->select(
                's.store_key',
                's.store_name',
                DB::raw('SUM(sf.extended_gross_profit_amount) AS total_gross_profit')
            )
            ->groupBy('s.store_key', 's.store_name')
            ->orderBy('s.store_key')
            ->get();

        return [
            'labels'      => $data->pluck('store_key')->toArray(),
            'store_names' => $data->pluck('store_name')->toArray(),
            'values'      => $data->pluck('total_gross_profit')->map(fn ($v) => round($v, 2))->toArray(),
        ];
    }

    public function getQuantityAwalHari(Request $request)
    {
        $query = DB::table('inventory_periodic_fact as ip')
            ->join('store_dimension as s', 'ip.store_key', '=', 's.store_key')
            ->join('product_dimension as p', 'ip.product_key', '=', 'p.product_key')
            ->join('date_dimension as d', 'ip.date_key', '=', 'd.date_key');
        
        $productKey = $request->input('inventory_product_key');
        $storeKey = $request->input('inventory_store_key');
        $startDateParam = $request->input('inventory_start_date');
        $endDateParam = $request->input('inventory_end_date');

        if (!empty($productKey)) {
            $query->where('ip.product_key', $productKey);
        }

        if (!empty($storeKey)) {
            $query->where('ip.store_key', $storeKey);
        }

        if (!empty($startDateParam)) {
            $query->where('ip.date_key', '>=', str_replace('-', '', $startDateParam));
        }

        if (!empty($endDateParam)) {
            $query->where('ip.date_key', '<=', str_replace('-', '', $endDateParam));
        }

        $data = $query->select(
                'd.date',
                DB::raw('SUM(ip.quantity_on_hand) AS qty_awal')
            )
            ->groupBy('d.date')
            ->orderBy('d.date')
            ->get();

        return [
            'labels' => $data->map(fn ($row) => Carbon::parse($row->date)->format('d/m/Y'))->toArray(),
            'values' => $data->pluck('qty_awal')->toArray(),
        ];
    }

    public function getQuantityAkhirHari(Request $request)
    {
        $query = DB::table('inventory_periodic_fact as ip')
            ->join('store_dimension as s', 'ip.store_key', '=', 's.store_key')
            ->join('product_dimension as p', 'ip.product_key', '=', 'p.product_key')
            ->join('date_dimension as d', 'ip.date_key', '=', 'd.date_key');

        $productKey = $request->input('inventory_product_key');
        $storeKey = $request->input('inventory_store_key');
        $startDateParam = $request->input('inventory_start_date');
        $endDateParam = $request->input('inventory_end_date');

        if (!empty($productKey)) {
            $query->where('ip.product_key', $productKey);
        }

        if (!empty($storeKey)) {
            $query->where('ip.store_key', $storeKey);
        }

        if (!empty($startDateParam)) {
            $query->where('ip.date_key', '>=', str_replace('-', '', $startDateParam));
        }

        if (!empty($endDateParam)) {
            $query->where('ip.date_key', '<=', str_replace('-', '', $endDateParam));
        }

        $data = $query->select(
                'd.date',
                DB::raw('SUM(ip.final_quantity_on_hand) AS qty_akhir')
            )
            ->groupBy('d.date')
            ->orderBy('d.date')
            ->get();

        return [
            'labels' => $data->map(fn ($row) => Carbon::parse($row->date)->format('d/m/Y'))->toArray(),
            'values' => $data->pluck('qty_akhir')->toArray(),
        ];
    }

}