<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailSalesFactSeeder extends Seeder
{   
    private function _isHoliday(int $dateKey): bool {
        if (DB::table("date_dimension")->where("date_key", $dateKey)->first()->holiday_indicator) {
            return true;
        } else {
            return false;
        }
    }

    private function _getCountTransaction(int $dateKey): int {
        $countTransaction = random_int(0, 3);
        if ($this->_isHoliday($dateKey)) {
            $countTransaction += random_int(1, 3);
        }
        return $countTransaction;
    }

    private function _getCountUniqueProducts(int $dateKey): int {
        $countUniqueProducts = random_int(1, 4);
        if ($this->_isHoliday($dateKey)) {
            $countUniqueProducts += random_int(1, 2);
        }
        return $countUniqueProducts;
    }

    private function _getProductKey(): int {
        return random_int(1, 50);
    }

    private function _getStoreCashierKey(): array {
        $cashier = random_int(1, 40);
        $store =  intdiv(($cashier - 1), 2) + 1;
        return array($store, $cashier);
    }

    private function _getPromotionKey(int $productKey, int $dateKey): int {
        if ($productKey % 2 == 0) {  // no promotion
            return 1;
        } else {  // item is said to be promoted
            return random_int(2, 10);
        }
    }

    private function _getPaymentMethodKey(int $storeKey): int {
        if ($storeKey % 2 == 0) {
            return random_int(1, 3);
        } else {
            return random_int(2, 8);
        }
    }

    private function _getSalesQuantity(int $productKey, int $promotionKey): int {
        if (DB::table("promotion_dimension")->where("promotion_key", $promotionKey)->first()->promotion_code != "NA") {
            return random_int(1, 7);
        } else {
            return random_int(1, 4);
        }
    }

    private function _getRegularUnitPrice(int $productKey): float {
        $regularUnitPrice = 12000;
        if ($productKey < 10) {
            $regularUnitPrice *= 2.5;
        } elseif ($productKey < 20) {
            $regularUnitPrice *= 3.5;
        } elseif ($productKey < 30) {
            $regularUnitPrice += 14000;
            if ($productKey % 2 == 0) {
                $regularUnitPrice *= 1.1;
            }
        } elseif ($productKey < 40) {
            if ($productKey % 2 == 1) {
                $regularUnitPrice *= 0.7;
            } else {
                $regularUnitPrice = 27400;
                if ($productKey > 46) {
                    $regularUnitPrice *= 1.2;
                }
            }
        }
        return $regularUnitPrice;
    }

    private function _getUnitCost(int $productKey): float {
        if ($productKey < 24) {
            if ($productKey % 2 == 0) {
                return $this->_getRegularUnitPrice($productKey) - 600;
            } else {
                return $this->_getRegularUnitPrice($productKey) * 0.85 - 500;
            }
        } elseif ($productKey < 37) {
            return $this->_getRegularUnitPrice($productKey) * 0.9 - 4000;
        } else {
            return $this->_getRegularUnitPrice($productKey) * 0.7;
        }
    }

    private function _getDiscountUnitPrice(int $productKey, int $promotionKey): float {
        if (DB::table("promotion_dimension")->where("promotion_key", $promotionKey)->first()->promotion_code == "NA") {
            return 0;
        } else {
            return 0.12 * $this->_getRegularUnitPrice($productKey);
        }
    }

    private function _getNetUnitPrice(float $regularUnitPrice, float $discountUnitPrice): float {
        return $regularUnitPrice - $discountUnitPrice;
    }

    private function _getExtendedDiscountAmount(int $salesQuantity, float $discountUnitPrice): float {
        return $salesQuantity * $discountUnitPrice;
    }

    private function _getExtendedSalesAmount(int $salesQuantity, float $netUnitPrice): float {
        return $salesQuantity * $netUnitPrice;
    }

    private function _getExtendedCostAmount (int $salesQuantity, float $unitCost): float {
        return $salesQuantity * $unitCost;
    }

    private function _getExtendedGrossProfitAmount (float $extendedSalesAmount, float $extendedCostAmount): float {
        return $extendedSalesAmount - $extendedCostAmount;
    }

    private function _getExtendedGrossMarginAMount(float $extendedGrossProfitAmount, float $extendedSalesAmount): float {
        return $extendedGrossProfitAmount / $extendedSalesAmount;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dates = DB::table("date_dimension")->get();
        foreach ($dates as $date) {
            $dateKey = $date->date_key;
            $countTransaction = $this->_getCountTransaction($dateKey);
            for ($iTransaction = 0; $iTransaction < $countTransaction; $iTransaction++) {
                $countUniqueProducts = $this->_getCountUniqueProducts($dateKey);
                for ($iUniqueProduct = 0; $iUniqueProduct < $countUniqueProducts; $iUniqueProduct++) {
                    $productKey = $this->_getProductKey();
                    $storeCashierKey = $this->_getStoreCashierKey();
                    $storeKey = $storeCashierKey[0];
                    $cashierKey = $storeCashierKey[1];
                    $promotionKey = $this->_getPromotionKey($productKey, $dateKey);
                    $paymentMethodKey = $this->_getPaymentMethodKey($storeKey);
                    $salesQuantity = $this->_getSalesQuantity($productKey, $promotionKey);
                    $regularUnitPrice = $this->_getRegularUnitPrice($productKey);
                    $unitCost = $this->_getUnitCost($productKey);
                    $discountUnitPrice = $this->_getDiscountUnitPrice($productKey, $promotionKey);
                    $netUnitPrice = $this->_getNetUnitPrice($regularUnitPrice, $discountUnitPrice);
                    $extendedDiscountAmount = $this->_getExtendedDiscountAmount($salesQuantity, $discountUnitPrice);
                    $extendedSalesAmount = $this->_getExtendedSalesAmount($salesQuantity, $netUnitPrice);
                    $extendedCostAmount = $this->_getExtendedCostAmount($salesQuantity, $unitCost);
                    $extendedGrossProfitAmount = $this->_getExtendedGrossProfitAmount($extendedSalesAmount, $extendedCostAmount);
                    $extendedGrossMarginAmount = $this->_getExtendedGrossMarginAMount($extendedGrossProfitAmount, $extendedSalesAmount);

                    DB::table("retail_sales_fact")->insert([
                        "date_key" => $dateKey,
                        "product_key" => $productKey,
                        "store_key" => $storeKey,
                        "cashier_key" => $cashierKey,
                        "promotion_key" => $promotionKey,
                        "payment_method_key" => $paymentMethodKey,
                        "transaction_id" => $iTransaction,
                        "sales_quantity" => $salesQuantity,
                        "regular_unit_price" => $regularUnitPrice,
                        "unit_cost" => $unitCost,
                        "discount_unit_price" => $discountUnitPrice,
                        "net_unit_price" => $netUnitPrice,
                        "extended_discount_amount" => $extendedDiscountAmount,
                        "extended_sales_amount" => $extendedSalesAmount,
                        "extended_cost_amount" => $extendedCostAmount,
                        "extended_gross_profit_amount" => $extendedGrossProfitAmount,
                        "extended_gross_margin_amount" => $extendedGrossMarginAmount
                    ]);
                }
            }
        }
    }
}
