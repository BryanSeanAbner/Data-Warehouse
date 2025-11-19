# 🚀 Quick Start: Full Format SQL Export Processing

## Scenario
Anda sudah punya file CSV export dari `retail_sales_fact` dengan **18 kolom lengkap**. File ini akan diproses ulang dengan Hadoop MapReduce untuk aggregasi dan re-import.

---

## 📁 File yang Tersedia

```
storage/hadoop/input/1763574428_retail_sales_fact.csv
```

**Format:** 18 kolom (id, date_key, product_key, store_key, cashier_key, promotion_key, payment_method_key, transaction_id, sales_quantity, regular_unit_price, unit_cost, discount_unit_price, net_unit_price, extended_discount_amount, extended_sales_amount, extended_cost_amount, extended_gross_profit_amount, extended_gross_margin_amount)

**Rows:** ~10,000+ transaksi

---

## ⚡ Step-by-Step

### Step 1: Verifikasi File CSV

```bash
# Di Windows PowerShell
wsl head -5 /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/input/1763574428_retail_sales_fact.csv
```

**Expected:** Header + 4 data rows dengan 18 kolom

---

### Step 2: Run Hadoop MapReduce

```bash
# Buka WSL terminal
cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts

# Run ETL sebagai root
sudo bash run_etl_root.sh
```

**Expected Output:**
```
🚀 Starting Hadoop ETL Pipeline...
📁 Input files: 1763574428_retail_sales_fact.csv
🗑️  Cleaning HDFS directories...
📤 Uploading CSV to HDFS...
⚙️  Running MapReduce job...
📥 Downloading results from HDFS...
✅ Results saved to: /mnt/c/.../processed/sales_aggregated_20251120_HHMMSS.tsv

📊 Preview hasil (5 baris pertama):
date_key | product_key | store_key | cashier_key | promotion_key | payment_method_key | ...
-------------------------------------------------------------------
20240101 | 10 | 19 | 38 | 1 | 6 | 2 | 42000.00 | 41400.00 | ...

📈 Statistik:
Total rows processed: XXXX
```

---

### Step 3: Import TSV ke MySQL

```bash
# Di Windows PowerShell (Laravel directory)
php artisan hadoop:import sales_aggregated_20251120_HHMMSS.tsv
```

**Replace** `HHMMSS` dengan timestamp actual dari output Step 2.

**Expected Output:**
```
📖 Reading Hadoop output: sales_aggregated_20251120_HHMMSS.tsv
XXXX [============================]

 Import completed!
+---------------------+-------+
| Metric              | Value |
+---------------------+-------+
| Total rows imported | XXXX  |
| Errors skipped      | 0     |
| File                | sales_aggregated_20251120_HHMMSS.tsv |
+---------------------+-------+
```

---

### Step 4: Verifikasi Data di MySQL

```bash
php artisan tinker --execute="DB::table('retail_sales_fact')->count();"
```

**Expected:** Jumlah rows bertambah sesuai import

---

## 🎯 Keuntungan Full Format

### ✅ Preserves All Fields
- Cashier key tetap preserved
- Promotion key tetap preserved  
- Payment method key tetap preserved
- Transaction ID tetap preserved

### ✅ Accurate Calculations
- Unit prices: Langsung dari database
- Unit cost: Actual cost, bukan estimasi
- Margin: Actual margin, bukan asumsi 30%

### ✅ Better Aggregation
- Aggregate by 6 dimensions (date, product, store, cashier, promotion, payment)
- More granular analysis
- Better business insights

---

## 📊 Output Format Comparison

### Simple Format (7 columns)
```
date_key | product_id | store_id | qty | gross | discount | net
20240115 | 101 | 1 | 2 | 100000.00 | 5000.00 | 95000.00
```

**Missing:** cashier, promotion, payment method, actual costs

---

### Full Format (16 columns)
```
date_key | product_key | store_key | cashier_key | promotion_key | payment_method_key | sales_qty | regular_price | unit_cost | discount_price | net_price | extended_discount | extended_sales | extended_cost | extended_profit | margin
20240101 | 10 | 19 | 38 | 1 | 6 | 2 | 42000.00 | 41400.00 | 0.00 | 42000.00 | 0.00 | 84000.00 | 82800.00 | 1200.00 | 0.0100
```

**Complete:** All dimensions + accurate metrics

---

## 🔄 Re-processing Workflow

```
MySQL (retail_sales_fact)
    ↓ Export
CSV (18 columns)
    ↓ Upload to Hadoop
HDFS Input
    ↓ MapReduce
HDFS Output (aggregated)
    ↓ Download
TSV (16 columns)
    ↓ Import
MySQL (retail_sales_fact) - Updated
```

---

## 💡 Use Cases

### 1. Data Cleansing
- Export → Clean duplicates with Hadoop → Re-import

### 2. Re-aggregation
- Export → Aggregate by different dimensions → Re-import

### 3. Data Transformation
- Export → Transform with custom logic → Re-import

### 4. Backup & Restore
- Export → Store in HDFS → Restore when needed

---

## 🐛 Common Issues

### Issue 1: "File not found" saat import
**Cause:** Nama file TSV salah atau tidak ada
**Solution:** 
```bash
# List processed files
ls -lh storage/hadoop/processed/

# Use exact filename
php artisan hadoop:import sales_aggregated_YYYYMMDD_HHMMSS.tsv
```

---

### Issue 2: MapReduce job stuck
**Cause:** Hadoop process hanging
**Solution:**
```bash
# Kill Hadoop processes
sudo pkill -f hadoop

# Re-run
sudo bash run_etl_root.sh
```

---

### Issue 3: Import errors (foreign key)
**Cause:** Seharusnya tidak terjadi (FK checks disabled)
**Solution:**
```bash
# Check if FK checks disabled in ImportHadoopData.php
grep "FOREIGN_KEY_CHECKS" app/Console/Commands/ImportHadoopData.php
```

---

## 📈 Performance Tips

1. **Batch Size:** Import uses 500 rows per batch (optimal)
2. **File Size:** Full format ~2x larger than simple, but more accurate
3. **Processing Time:** ~10-15 seconds per 10,000 rows
4. **Memory:** Reducer uses minimal memory (streaming)

---

## ✅ Success Checklist

- [ ] CSV file exists in `storage/hadoop/input/`
- [ ] CSV has 18 columns (full format)
- [ ] MapReduce completed successfully
- [ ] TSV file generated in `storage/hadoop/processed/`
- [ ] TSV has 16 columns
- [ ] Import command runs without errors
- [ ] Data count increased in MySQL
- [ ] Dashboard shows updated metrics

---

**Ready to process!** 🎉

Run the commands above and watch your data flow through the Hadoop pipeline!
