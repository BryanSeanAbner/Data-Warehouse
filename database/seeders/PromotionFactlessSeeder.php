<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionFactlessSeeder extends Seeder
{
    private function _isPromoted($product, $promotion): bool {
        $productKey = $product->product_key;
        $promotionKey = $promotion->promotion_key;
        if (($productKey % 2 == 0 and $promotionKey % 2 == 0) or ($productKey % 2 == 1 and $promotionKey % 2 == 1)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for each promotion:
            // for each product:
                // if is_promoted(product):
                    // insert_table('promotion_factless', product.product_key, promotion.promotion_key)
        
        $promotions = DB::table('promotion_dimension')->get();
        foreach ($promotions as $promotion) {
            $products = DB::table('product_dimension')->get();
            foreach ($products as $product) {
                if ($this->_isPromoted($product, $promotion)) {
                    DB::table('promotion_factless')->insert([
                        "product_key" => $product->product_key,
                        "promotion_key" => $promotion->promotion_key,
                    ]);
                }
            }
        }
    }
}
