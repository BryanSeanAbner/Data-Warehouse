<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('store_dimension')->insert([
            [
                'store_name' => 'Dipati Unus',
                'store_street_address' => 'Jl. Dipati Unus',
                'store_subdistrict' => 'Cibodas',
                'store_district' => 'Cibodas',
                'store_city' => 'Tangerang',
                'store_province' => 'Banten',
            ],

            [
                'store_name' => 'Raden Fatah',
                'store_street_address' => 'Jl. Raden Fatah',
                'store_subdistrict' => 'Sudimara Barat',
                'store_district' => 'Ciledug',
                'store_city' => 'Tangerang',
                'store_province' => 'Banten',
            ],

            [
                'store_name' => 'Raya Cicadas',
                'store_street_address' => 'Jl. Raya Cicadas',
                'store_subdistrict' => 'Ciater',
                'store_district' => 'Serpong',
                'store_city' => 'Tangerang Selatan',
                'store_province' => 'Banten',
            ],

            [
                'store_name' => 'Wahyudi',
                'store_street_address' => 'Jl. Wahyudi',
                'store_subdistrict' => 'Pondok Cabe',
                'store_district' => 'Pamulang',
                'store_city' => 'Tangerang Selatan',
                'store_province' => 'Banten',
            ],

            [
                'store_name' => 'Kamal Raya',
                'store_street_address' => 'Jl. Kamal Raya',
                'store_subdistrict' => 'Cengkareng Barat',
                'store_district' => 'Cengkareng',
                'store_city' => 'Jakarta Barat',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Anggrek Garuda',
                'store_street_address' => 'Jl. Anggrek Garuda',
                'store_subdistrict' => 'Slipi',
                'store_district' => 'Palmerah',
                'store_city' => 'Jakarta Barat',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Letjen Suprapto',
                'store_street_address' => 'Jl. Letjen Suprapto',
                'store_subdistrict' => 'Cempaka Baru',
                'store_district' => 'Kemayoran',
                'store_city' => 'Jakarta Pusat',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Cempaka Putih Tengah',
                'store_street_address' => 'Jl. Cempaka Putih Tengah',
                'store_subdistrict' => 'Rawasari',
                'store_district' => 'Cempaka Putih',
                'store_city' => 'Jakarta Pusat',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Manggarai Utara',
                'store_street_address' => 'Jl. Manggarai Utara',
                'store_subdistrict' => 'Manggarai',
                'store_district' => 'Tebet',
                'store_city' => 'Jakarta Selatan',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Villa Jatipadang',
                'store_street_address' => 'Jl. Villa Jatipadang',
                'store_subdistrict' => 'Jati Padang',
                'store_district' => 'Pasar Minggu',
                'store_city' => 'Jakarta Pusat',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Jatinegara Kaum',
                'store_street_address' => 'Jl. Jatinegara Kaum',
                'store_subdistrict' => 'Jatinegara',
                'store_district' => 'Cakung',
                'store_city' => 'Jakarta Timur',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Kelapa Hijau',
                'store_street_address' => 'Jl. Kelapa Hijau',
                'store_subdistrict' => 'Kayu Manis',
                'store_district' => 'Matraman',
                'store_city' => 'Jakarta Timur',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Yos Sudarso',
                'store_street_address' => 'Jl. Yos Sudarso',
                'store_subdistrict' => 'Ancol',
                'store_district' => 'Pademangan',
                'store_city' => 'Jakarta Utara',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Monas',
                'store_street_address' => 'Jl. Monas',
                'store_subdistrict' => 'Tugu Utara',
                'store_district' => 'Koja',
                'store_city' => 'Jakarta Utara',
                'store_province' => 'DKI Jakarta',
            ],

            [
                'store_name' => 'Raya Bekasi',
                'store_street_address' => 'Jl. Raya Bekasi',
                'store_subdistrict' => 'Medan Satria',
                'store_district' => 'Medan Satria',
                'store_city' => 'Bekasi',
                'store_province' => 'Jawa Barat',
            ],

            [
                'store_name' => 'Raya Mustika Sari',
                'store_street_address' => 'Jl. Raya Mustika Sari',
                'store_subdistrict' => 'Mustikasari',
                'store_district' => 'Mustika Jaya',
                'store_city' => 'Bekasi',
                'store_province' => 'Jawa Barat',
            ],

            [
                'store_name' => 'Cilember',
                'store_street_address' => 'Jl. Cilember',
                'store_subdistrict' => 'Curug',
                'store_district' => 'Bogor Barat',
                'store_city' => 'Bogor',
                'store_province' => 'Jawa Barat',
            ],

            [
                'store_name' => 'Raya Aneka Tambang',
                'store_street_address' => 'Jl. Raya Aneka Tambang',
                'store_subdistrict' => 'Cikaret',
                'store_district' => 'Bogor Selatan',
                'store_city' => 'Bogor',
                'store_province' => 'Jawa Barat',
            ],

            [
                'store_name' => 'Palakali Raya',
                'store_street_address' => 'Jl. Palakali Raya',
                'store_subdistrict' => 'Kukusan',
                'store_district' => 'Beji',
                'store_city' => 'Depok',
                'store_province' => 'Jawa Barat',
            ],

            [
                'store_name' => 'Abdul Rohim',
                'store_street_address' => 'Jl. Abdul Rohim',
                'store_subdistrict' => 'Bojongsari Baru',
                'store_district' => 'Bojongsari',
                'store_city' => 'Depok',
                'store_province' => 'Jawa Barat',
            ],
        ]);
    }
}
