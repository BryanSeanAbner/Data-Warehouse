<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        // Statistik keseluruhan
        $totalRecords = DB::table('retail_sales_fact')->count();
        $totalGrossProfit = DB::table('retail_sales_fact')->sum('gross_profit');
        $avgGrossProfit = DB::table('retail_sales_fact')->avg('gross_profit');
        $maxGrossProfit = DB::table('retail_sales_fact')->max('gross_profit');
        $minGrossProfit = DB::table('retail_sales_fact')->min('gross_profit');

        return view('dashboard', [
            'totalRecords' => $totalRecords,
            'totalGrossProfit' => $totalGrossProfit,
            'avgGrossProfit' => $avgGrossProfit,
            'maxGrossProfit' => $maxGrossProfit,
            'minGrossProfit' => $minGrossProfit,
        ]);
    }
}


