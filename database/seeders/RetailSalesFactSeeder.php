<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RetailSalesFactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Konfigurasi periode 1 bulan terakhir
        $startDate = Carbon::now()->startOfMonth();
        $endDate = (clone $startDate)->endOfMonth();

        // Ambil semua kunci dimensi yang diperlukan
        $dateKeys = DB::table('date_dimension')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->orderBy('date')
            ->pluck('date_key', 'date')
            ->toArray();

        // Tambahkan debug jika dimensi kosong ataupun =0
        $productKeys = DB::table('product_dimension')->pluck('product_key')->toArray();
        $storeKeys = DB::table('store_dimension')->pluck('store_key')->toArray();
        $cashierKeys = DB::table('cashier_dimension')->pluck('cashier_key')->toArray();
        $promotionKeys = DB::table('promotion_dimension')->pluck('promotion_key')->toArray();
        $paymentMethodKeys = DB::table('payment_method_dimension')->pluck('payment_method_key')->toArray();

        // Validasi dimensi yang wajib (date, product, store)
        if (empty($dateKeys)) {
            dump("[SEED ERROR] Tabel date_dimension kosong atau tidak ada data untuk periode bulan ini. Jalankan DateDimensionSeeder terlebih dahulu!");
            return;
        }
        
        if (empty($productKeys)) {
            dump("[SEED ERROR] Tabel product_dimension kosong. Jalankan ProductDimensionSeeder terlebih dahulu!");
            return;
        }
        
        if (empty($storeKeys)) {
            dump("[SEED ERROR] Tabel store_dimension kosong. Jalankan StoreDimensionSeeder terlebih dahulu!");
            return;
        }

		// Dimensi tambahan opsional: jika belum ada, buat record default
		if (empty($cashierKeys)) {
			dump("[SEED WARNING] Tabel cashier_dimension kosong. Membuat record default.");
			// Buat record default untuk cashier
			$defaultCashierKey = DB::table('cashier_dimension')->insertGetId([
				'cashier_name' => 'Default Cashier',
				'hire_date' => Carbon::now()->toDateString(),
				'shift_type' => 'Full Time',
				'hire_source' => 'System'
			]);
			$cashierKeys = [$defaultCashierKey];
		}
		
		if (empty($promotionKeys)) {
			dump("[SEED WARNING] Tabel promotion_dimension kosong. Membuat record default.");
			// Buat record default untuk promotion
			$defaultPromotionKey = DB::table('promotion_dimension')->insertGetId([
				'promotion_code' => 'NONE',
				'promotion_name' => 'No Promotion',
				'promotion_media_type' => 'None',
				'promotion_begin_date' => Carbon::now()->subYear()->toDateString(),
				'promotion_end_date' => Carbon::now()->addYear()->toDateString()
			]);
			$promotionKeys = [$defaultPromotionKey];
		}
		
		if (empty($paymentMethodKeys)) {
			dump("[SEED WARNING] Tabel payment_method_dimension kosong. Membuat record default.");
			// Buat record default untuk payment method
			$defaultPaymentMethodKey = DB::table('payment_method_dimension')->insertGetId([
				'payment_method_description' => 'Cash',
				'payment_method_group' => 'Cash'
			]);
			$paymentMethodKeys = [$defaultPaymentMethodKey];
		}

        // Hapus semua data agar idempotent
        DB::table('retail_sales_fact')->truncate();

        $rows = [];
        $transactionId = 1;
        
        // Generate hanya 100 baris data
        for ($i = 0; $i < 100; $i++) {
            // Pilih random date, product, store
            $randomDateKey = array_values($dateKeys)[array_rand(array_values($dateKeys))];
            $randomProductKey = $productKeys[array_rand($productKeys)];
            $randomStoreKey = $storeKeys[array_rand($storeKeys)];
            
            // Generate gross profit random antara 100.00 - 10000.00
            $grossProfit = round(random_int(10000, 1000000) / 100, 2);

            $rows[] = [
                'date_key' => (int) $randomDateKey,
                'product_key' => (int) $randomProductKey,
                'store_key' => (int) $randomStoreKey,
                'cashier_key' => (int) $cashierKeys[array_rand($cashierKeys)],
                'promotion_key' => (int) $promotionKeys[array_rand($promotionKeys)],
                'payment_method_key' => (int) $paymentMethodKeys[array_rand($paymentMethodKeys)],
                'transaction_id' => $transactionId++,
                'gross_profit' => $grossProfit,
            ];
        }

        // Insert bertahap untuk efisiensi memori
        if (empty($rows)) {
            dump("[SEED WARNING] Tidak ada data yang akan di-insert. Pastikan semua dimensi sudah di-seed.");
            return;
        }

        try {
            foreach (array_chunk($rows, 1000) as $chunk) {
                DB::table('retail_sales_fact')->insert($chunk);
            }
            dump("[SEED SUCCESS] Berhasil insert " . count($rows) . " baris data ke retail_sales_fact.");
        } catch (\Exception $e) {
            dump("[SEED ERROR] Gagal insert data: " . $e->getMessage());
            throw $e;
        }
    }
}


