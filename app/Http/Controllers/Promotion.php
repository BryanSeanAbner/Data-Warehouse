<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Promotion extends Controller
{
    public function index(Request $request)
    {
        $promotionKey = $request->input('promotion_key');
        $storeKey = $request->input('store_key');
        $perPage = $request->input('per_page', 25);

        // Get promotions for dropdown
        $promotions = DB::table('promotion_dimension')
            ->select('promotion_key', 'promotion_name', 'promotion_code')
            ->orderBy('promotion_name')
            ->get();

        // Get stores for dropdown
        $stores = DB::table('store_dimension')
            ->select('store_key', 'store_name')
            ->orderBy('store_name')
            ->get();

        // Build query for promoted products gross profit only if promotion_key is provided
        if (!empty($promotionKey)) {
            $query = DB::table('retail_sales_fact as rsf')
                ->join('product_dimension as pd', 'rsf.product_key', '=', 'pd.product_key')
                ->whereIn('rsf.product_key', function($subquery) use ($promotionKey) {
                    $subquery->select('promotion_factless.product_key')
                        ->from('promotion_factless')
                        ->join('promotion_dimension', 'promotion_dimension.promotion_key', '=', 'promotion_factless.promotion_key')
                        ->where('promotion_dimension.promotion_key', $promotionKey)
                        ->distinct();
                });

            // Apply store filter if provided
            if (!empty($storeKey)) {
                $query->where('rsf.store_key', $storeKey);
            }

            // Select and group
            $query->select(
                    'rsf.product_key',
                    'pd.product_description',
                    DB::raw('SUM(rsf.extended_gross_profit_amount) as sum_gross_profit')
                )
                ->groupBy('rsf.product_key', 'pd.product_description')
                ->orderBy('sum_gross_profit', 'desc');

            // Get results with pagination
            $promotedProducts = $query->limit(10)->get();

            // Query untuk produk yang tidak terjual selama promosi
            // Produk yang ada di promotion_factless tapi tidak ada di retail_sales_fact
            // selama periode promosi
            $promotion = DB::table('promotion_dimension')
                ->where('promotion_key', $promotionKey)
                ->first();

            if ($promotion) {
                $unsoldProductsQuery = DB::table('promotion_factless as pf')
                    ->join('product_dimension as pd', 'pf.product_key', '=', 'pd.product_key')
                    ->join('promotion_dimension as p', 'pf.promotion_key', '=', 'p.promotion_key')
                    ->where('pf.promotion_key', $promotionKey)
                    ->where('pf.product_key', '!=', 0) // Exclude "Not Applicable" products
                    ->whereNotExists(function($query) use ($promotionKey, $storeKey, $promotion) {
                        $query->select(DB::raw(1))
                            ->from('retail_sales_fact as rsf')
                            ->join('date_dimension as dd', 'rsf.date_key', '=', 'dd.date_key')
                            ->whereColumn('rsf.product_key', 'pf.product_key')
                            ->where('rsf.promotion_key', $promotionKey)
                            ->whereBetween('dd.date', [$promotion->promotion_begin_date, $promotion->promotion_end_date]);
                        
                        if (!empty($storeKey)) {
                            $query->where('rsf.store_key', $storeKey);
                        }
                    })
                    ->select(
                        'pd.product_key',
                        'pd.sku_number',
                        'pd.product_description',
                        'pd.brand_name',
                        'pd.category_name',
                        'p.promotion_name',
                        'p.promotion_code',
                        'p.promotion_begin_date',
                        'p.promotion_end_date'
                    )
                    ->orderBy('pd.product_description');

                $unsoldProducts = $unsoldProductsQuery->get();
            } else {
                $unsoldProducts = collect([]);
            }
        } else {
            // Return empty paginator if no promotion selected
            $promotedProducts = DB::table('retail_sales_fact')
                ->whereRaw('1 = 0') // Always false condition
                ->select('product_key', DB::raw('NULL as product_description'), DB::raw('0 as sum_gross_profit'))
                ->paginate($perPage)->withQueryString();
            
            $unsoldProducts = collect([]);
        }

        // Get selected promotion and store info for display
        $selectedPromotion = null;
        if ($promotionKey) {
            $selectedPromotion = DB::table('promotion_dimension')
                ->where('promotion_key', $promotionKey)
                ->first();
        }

        $selectedStore = null;
        if ($storeKey) {
            $selectedStore = DB::table('store_dimension')
                ->where('store_key', $storeKey)
                ->first();
        }

        return view('promotion', [
            'promotedProducts' => $promotedProducts,
            'unsoldProducts' => $unsoldProducts ?? collect([]),
            'promotions' => $promotions,
            'stores' => $stores,
            'selectedPromotion' => $selectedPromotion,
            'selectedStore' => $selectedStore,
            'promotionKey' => $promotionKey,
            'storeKey' => $storeKey,
            'perPage' => $perPage,
        ]);
    }
}
