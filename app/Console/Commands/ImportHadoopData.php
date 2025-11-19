<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportHadoopData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'hadoop:import {file : Nama file TSV hasil Hadoop}';

    /**
     * The console command description.
     */
    protected $description = 'Import Hadoop MapReduce output (TSV) ke tabel retail_sales_fact';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->argument('file');
        $path = storage_path("hadoop/processed/{$filename}");
        
        if (!file_exists($path)) {
            $this->error(" File not found: {$path}");
            return Command::FAILURE;
        }

        $this->info("📖 Reading Hadoop output: {$filename}");
        
        // Disable foreign key checks untuk import
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $handle = fopen($path, 'r');
        
        if (!$handle) {
            $this->error(" Cannot open file: {$path}");
            return Command::FAILURE;
        }

        $rows = [];
        $count = 0;
        $errors = 0;
        $bar = $this->output->createProgressBar();
        $bar->start();

        while (($line = fgets($handle)) !== false) {
            try {
                // Support 2 formats:
                // 1. Simple: date_key \t product_id \t store_id \t qty \t gross \t discount \t net (7 cols)
                // 2. Full: date_key \t product_key \t store_key \t cashier_key \t promotion_key \t payment_method_key \t 
                //          sales_quantity \t regular_unit_price \t unit_cost \t discount_unit_price \t net_unit_price \t
                //          extended_discount \t extended_sales \t extended_cost \t extended_profit \t margin (16 cols)
                
                $parts = explode("\t", trim($line));
                $numParts = count($parts);
                
                if ($numParts == 16) {
                    // Full format from SQL export
                    $rows[] = [
                        'date_key' => (int)$parts[0],
                        'product_key' => (int)$parts[1],
                        'store_key' => (int)$parts[2],
                        'cashier_key' => (int)$parts[3],
                        'promotion_key' => (int)$parts[4],
                        'payment_method_key' => (int)$parts[5],
                        'transaction_id' => 0,
                        'sales_quantity' => (int)$parts[6],
                        'regular_unit_price' => (float)$parts[7],
                        'unit_cost' => (float)$parts[8],
                        'discount_unit_price' => (float)$parts[9],
                        'net_unit_price' => (float)$parts[10],
                        'extended_discount_amount' => (float)$parts[11],
                        'extended_sales_amount' => (float)$parts[12],
                        'extended_cost_amount' => (float)$parts[13],
                        'extended_gross_profit_amount' => (float)$parts[14],
                        'extended_gross_margin_amount' => (float)$parts[15],
                    ];
                } elseif ($numParts >= 7) {
                    // Simple format
                    $qty = (int)$parts[3];
                    $gross = (float)$parts[4];
                    $discount = (float)$parts[5];
                    $net = (float)$parts[6];
                    
                    $rows[] = [
                        'date_key' => (int)$parts[0],
                        'product_key' => (int)$parts[1],
                        'store_key' => (int)$parts[2],
                        'cashier_key' => 1,
                        'promotion_key' => 1,
                        'payment_method_key' => 1,
                        'transaction_id' => 0,
                        'sales_quantity' => $qty,
                        'regular_unit_price' => round($gross / $qty, 2),
                        'discount_unit_price' => round($discount / $qty, 2),
                        'net_unit_price' => round($net / $qty, 2),
                        'unit_cost' => round($net / $qty * 0.7, 2),
                        'extended_discount_amount' => $discount,
                        'extended_sales_amount' => $net,
                        'extended_cost_amount' => $net * 0.7,
                        'extended_gross_profit_amount' => $net * 0.3,
                        'extended_gross_margin_amount' => 0.3,
                    ];
                } else {
                    $errors++;
                    continue;
                }
                
                // Batch insert setiap 500 baris
                if (count($rows) >= 500) {
                    DB::table('retail_sales_fact')->insert($rows);
                    $count += count($rows);
                    $bar->advance(count($rows));
                    $rows = [];
                }
                
            } catch (\Exception $e) {
                $errors++;
                $this->warn("\n Error parsing line: " . $e->getMessage());
            }
        }

        // Insert sisa data
        if (!empty($rows)) {
            DB::table('retail_sales_fact')->insert($rows);
            $count += count($rows);
            $bar->advance(count($rows));
        }

        fclose($handle);
        $bar->finish();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->newLine(2);
        $this->info(" Import completed!");
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total rows imported', number_format($count)],
                ['Errors skipped', $errors],
                ['File', $filename],
            ]
        );

        return Command::SUCCESS;
    }
}
