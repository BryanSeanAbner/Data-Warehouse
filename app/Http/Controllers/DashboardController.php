<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // STATISTIK UTAMA (dengan filter)
        $query = DB::table('retail_sales_fact as sf');

        // Terapkan filter yang sama seperti di chart
        if ($request->filled('product_key')) {
            $query->where('sf.product_key', $request->product_key);
        }

        if ($request->filled('start_date')) {
            // Konversi YYYY-MM-DD ke YYYYMMDD
            $startDate = str_replace('-', '', $request->start_date);
            $query->where('sf.date_key', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            // Konversi YYYY-MM-DD ke YYYYMMDD
            $endDate = str_replace('-', '', $request->end_date);
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

        // DATA UNTUK CHART
        $chartData = $this->getChartData($request);

        return view('dashboard', compact(
            'totalRecords',
            'totalGrossProfit',
            'avgGrossProfit',
            'maxGrossProfit',
            'minGrossProfit',
            'products',
            'dates',
            'chartData'
        ));
    }

    // CHART DATA
    public function getChartData(Request $request)
    {
        $query = DB::table('retail_sales_fact as sf')
            ->join('store_dimension as s', 'sf.store_key', '=', 's.store_key')
            ->join('product_dimension as p', 'sf.product_key', '=', 'p.product_key')
            ->join('date_dimension as d', 'sf.date_key', '=', 'd.date_key');

        // FILTER PRODUK
        if ($request->filled('product_key')) {
            $query->where('sf.product_key', $request->product_key);
        }

        // FILTER RANGE TANGGAL
        // Konversi format YYYY-MM-DD ke YYYYMMDD
        if ($request->filled('start_date')) {
            $startDate = str_replace('-', '', $request->start_date);
            $query->where('sf.date_key', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = str_replace('-', '', $request->end_date);
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

}