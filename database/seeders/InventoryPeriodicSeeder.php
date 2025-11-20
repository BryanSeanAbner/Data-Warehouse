<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryPeriodicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = DB::table('store_dimension')->get();
        $stores = $stores->slice(0, 2);

        foreach ($stores as $store) {
            if ($store->store_key === 0) {
                continue;
            }

            $storeKey = $store->store_key;

            $products = DB::table('product_dimension')->get();
            $products = $products->slice(0, 25);
            
            foreach ($products as $product) {
                if ($product->product_key === 0) {
                    continue;
                }
                
                $productKey = $product->product_key;

                $dates = DB::table('date_dimension')->get();
                $qoh = null;
                $fqoh = null;
                
                foreach ($dates as $date) {
                    if ($date->date_key === 0) {
                        continue;
                    }

                    $dateKey = $date->date_key;
                    $dayOfWeek = $date->day_of_week;

                    if ($qoh === null) {
                        $qoh = random_int(10, 20);
                    } else {
                        $qoh = $fqoh;
                    }
                    if ($dayOfWeek === "Monday") {
                        $qoh += random_int(7, 17);
                        $qoh = min($qoh, 30);
                    }

                    $qos = random_int(0, 4);
			        while ($qos > $qoh) {
                        $qos -= 1;
                    }

                    $fqoh = $qoh - $qos;
                    
                    $numberOfTurns = 0;
                    if ($qoh > 0) {
                        $numberOfTurns = $qos / $qoh;
                    }

                    DB::table("inventory_periodic_fact")->insert([
                        "date_key" => $dateKey,
                        "product_key" => $productKey,
                        "store_key" => $storeKey,
                        "quantity_of_sold" => $qos,
                        "quantity_on_hand" => $qoh,
                        "final_quantity_on_hand" => $fqoh,
                        "number_of_turns" => $numberOfTurns
                    ]);
                }
            }
        }
    }
}
