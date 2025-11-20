<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AutoIncrementSeeder;

class WarehouseSeeder extends AutoIncrementSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('warehouse_dimension')->insert([
            [
                'warehouse_key' => 0,
                'warehouse_number' => 0,
                'warehouse_name' => 'Not Applicable',
                'warehouse_address' => 'Not Applicable',
                'warehouse_city' => 'Not Applicable',
            ],
            [
                'warehouse_key' => $this->getIncrement(),
                'warehouse_number' => 4,
                'warehouse_name' => 'Gudang Alfa',
                'warehouse_address' => 'Jl. Pegangsaan 2 No. 3',
                'warehouse_city' => 'Jakarta Utara',
            ],
            [
                'warehouse_key' => $this->getIncrement(),
                'warehouse_number' => 5,
                'warehouse_name' => 'Gudang Beta',
                'warehouse_address' => 'Jl. Kota Baru Bandar Kemayoran No. 2',
                'warehouse_city' => 'Jakarta Pusat',
            ],
            [
                'warehouse_key' => $this->getIncrement(),
                'warehouse_number' => 6,
                'warehouse_name' => 'Gudang Gamma',
                'warehouse_address' => 'Jl. Tanjung Duren Raya No. 9',
                'warehouse_city' => 'Jakarta Barat',
            ],
            [
                'warehouse_key' => $this->getIncrement(),
                'warehouse_number' => 7,
                'warehouse_name' => 'Gudang Delta',
                'warehouse_address' => 'Jl. Letjen Suprapto No. 32',
                'warehouse_city' => 'Jakarta Timur',
            ]
        ]);
    }
}
