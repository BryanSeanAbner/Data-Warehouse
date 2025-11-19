<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportSqlToCsv extends Command
{
    protected $signature = 'export:sqlcsv';
    protected $description = 'Export SQL data to CSV';

    public function handle()
{
    // 1. Query SQL (ganti sesuai tabel kamu)
    $results = \DB::select("SELECT * FROM retail_sales_fact");
    $resultsArray = json_decode(json_encode($results), true);

    // 2. Tentukan lokasi file CSV
    $filePath = storage_path('app/export/retail_sales_fact.csv');

    // 3. Buka file
    $file = fopen($filePath, 'w');

    // 4. Mulai tulis data
    if (!empty($resultsArray)) {
        // Tulis header (nama kolom)
        fputcsv($file, array_keys($resultsArray[0]));

        // Tulis isi data
        foreach ($resultsArray as $row) {
            fputcsv($file, $row);
        }
    }

    // 5. Tutup file
    fclose($file);

    // 6. Informasi berhasil
    $this->info("CSV berhasil dibuat di: " . $filePath);
}
}