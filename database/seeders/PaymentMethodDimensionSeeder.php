<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_method_dimension')->insert([
            [
                'payment_method_description' => 'Cash',
                'payment_method_group' => 'Physical',
                
            ],
            [
                'payment_method_description' => 'Debit',
                'payment_method_group' => 'Card',
                
            ],
            [
                'payment_method_description' => 'Credit',
                'payment_method_group' => 'Card',
                
            ],
            [
                'payment_method_description' => 'BCA',
                'payment_method_group' => 'Bank Transfer',
                
            ],
            [
                'payment_method_description' => 'BNI',
                'payment_method_group' => 'Bank Transfer',
                
            ],
            [
                'payment_method_description' => 'GoPay',
                'payment_method_group' => 'E-Wallet',
                
            ],
            [
                'payment_method_description' => 'Dana',
                'payment_method_group' => 'E-Wallet',
                
            ],
            [
                'payment_method_description' => 'OVO',
                'payment_method_group' => 'E-Wallet',
                
            ],
            
        ]);
    }
}
