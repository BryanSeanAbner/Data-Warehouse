<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AutoIncrementSeeder;

class PaymentMethodDimensionSeeder extends AutoIncrementSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_method_dimension')->insert([
            [
                'payment_method_key' => 0,
                'payment_method_description' => 'Not Applicable',
                'payment_method_group' => 'Not Applicable'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'Cash',
                'payment_method_group' => 'Physical'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'Debit',
                'payment_method_group' => 'Card'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'Credit',
                'payment_method_group' => 'Card'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'BCA',
                'payment_method_group' => 'Bank Transfer'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'BNI',
                'payment_method_group' => 'Bank Transfer'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'GoPay',
                'payment_method_group' => 'E-Wallet'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'Dana',
                'payment_method_group' => 'E-Wallet'
            ],
            [
                'payment_method_key' => $this->getIncrement(),
                'payment_method_description' => 'OVO',
                'payment_method_group' => 'E-Wallet'
            ],
            
        ]);
    }
}
