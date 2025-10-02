<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PromotionDimensionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('promotion_dimension')->insert([
            [
                'promotion_code' => 'NEW',
                'promotion_name' => 'New Years Eve',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2024-12-26',
                'promotion_end_date' => '2025-01-04',
            ],
            [
                'promotion_code' => 'VAL',
                'promotion_name' => 'Valentine Day',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-02-01',
                'promotion_end_date' => '2025-02-28',
            ],
            [
                'promotion_code' => 'EAS',
                'promotion_name' => 'Easter',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-04-04',
                'promotion_end_date' => '2025-04-23',
            ],
            [
                'promotion_code' => 'HAL',
                'promotion_name' => 'Halloween',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-10-26',
                'promotion_end_date' => '2025-11-10',
            ],
            [
                'promotion_code' => 'CHR',
                'promotion_name' => 'Christmas',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-12-13',
                'promotion_end_date' => '2025-12-28',
            ],
            [
                'promotion_code' => 'CHI',
                'promotion_name' => 'Chinese New Year',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-01-25',
                'promotion_end_date' => '2025-02-11',
            ],
            [
                'promotion_code' => 'PAT',
                'promotion_name' => 'St. Patrick Day',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-03-13',
                'promotion_end_date' => '2025-03-20',
            ],
            [
                'promotion_code' => 'EID',
                'promotion_name' => 'Eid Mubarak',
                'promotion_media_type' => 'In-store',
                'promotion_begin_date' => '2025-03-21',
                'promotion_end_date' => '2025-04-05',
            ],
            [
                'promotion_code' => 'SCH',
                'promotion_name' => 'Back to School',
                'promotion_media_type' => 'Flyer',
                'promotion_begin_date' => '2025-07-10',
                'promotion_end_date' => '2025-07-29',
            ],
            [
                'promotion_code' => 'ANN',
                'promotion_name' => 'Anniversary',
                'promotion_media_type' => 'Flyer',
                'promotion_begin_date' => '2025-11-10',
                'promotion_end_date' => '2025-11-20',
            ],
        ]);
    }
}
