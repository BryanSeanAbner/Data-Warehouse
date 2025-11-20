<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AutoIncrementSeeder;

class VendorSeeder extends AutoIncrementSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vendor_dimension')->insert([
            [
                'vendor_key' => 0,
                'vendor_name' => 'Not Applicable',
                'vendor_address' => 'Not Applicable',
                'vendor_city' => 'Not Applicable',
            ],
            [
                'vendor_key' => $this->getIncrement(),
                'vendor_name' => 'PT Bangkit Perkasa Sukses',
                'vendor_address' => 'Jl. Kapuk Kayu Besar No. 18',
                'vendor_city' => 'Jakarta Barat',
            ],
            [
                'vendor_key' => $this->getIncrement(),
                'vendor_name' => 'PT Jaya Mandiri',
                'vendor_address' => 'ITC Mangga Dua Lt. 1 Blok E2 No. 119-120',
                'vendor_city' => 'Jakarta Utara',
            ],
            [
                'vendor_key' => $this->getIncrement(),
                'vendor_name' => 'PT Hamasa Iparna Mandiri',
                'vendor_address' => 'Jl. Ciherang Raya No. 3A',
                'vendor_city' => 'Depok',
            ]
        ]);
    }
}
