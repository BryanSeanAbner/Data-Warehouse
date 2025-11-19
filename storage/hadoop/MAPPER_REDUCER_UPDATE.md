# 🔄 Mapper & Reducer Update - Dual Format Support

## Overview

Mapper dan Reducer telah diupdate untuk mendukung **2 format CSV**:

### Format 1: Simple CSV (Manual Input)
```csv
transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount
1,2024-01-15,101,1,5,2,50000,5000
```

**Columns:** 8 kolom
**Use case:** Upload manual CSV transaksi toko

---

### Format 2: Full SQL Export (dari retail_sales_fact)
```csv
id,date_key,product_key,store_key,cashier_key,promotion_key,payment_method_key,transaction_id,sales_quantity,regular_unit_price,unit_cost,discount_unit_price,net_unit_price,extended_discount_amount,extended_sales_amount,extended_cost_amount,extended_gross_profit_amount,extended_gross_margin_amount
1,20240101,10,19,38,1,6,0,2,42000.00,41400.00,0.00,42000.00,0.00,84000.00,82800.00,1200.00,0.01
```

**Columns:** 18 kolom
**Use case:** Export dari MySQL → Re-process dengan Hadoop → Import kembali

---

## 📊 Data Flow

### Format 1 (Simple)
```
CSV (8 cols) → Mapper → TSV (7 cols) → Reducer → TSV (7 cols) → Import → MySQL
```

**Mapper Output:**
```
date_key|product_id|store_id \t quantity \t gross_amount \t discount \t net_amount
20240115|101|1 \t 2 \t 100000.00 \t 5000.00 \t 95000.00
```

**Reducer Output:**
```
date_key \t product_id \t store_id \t total_qty \t total_gross \t total_discount \t total_net
20240115 \t 101 \t 1 \t 2 \t 100000.00 \t 5000.00 \t 95000.00
```

---

### Format 2 (Full SQL Export)
```
CSV (18 cols) → Mapper → TSV (16 cols) → Reducer → TSV (16 cols) → Import → MySQL
```

**Mapper Output:**
```
date_key|product_key|store_key|cashier_key|promotion_key|payment_method_key \t sales_quantity \t regular_unit_price \t unit_cost \t discount_unit_price \t net_unit_price \t extended_discount \t extended_sales \t extended_cost \t extended_profit \t margin
20240101|10|19|38|1|6 \t 2 \t 42000.00 \t 41400.00 \t 0.00 \t 42000.00 \t 0.00 \t 84000.00 \t 82800.00 \t 1200.00 \t 0.0100
```

**Reducer Output:**
```
date_key \t product_key \t store_key \t cashier_key \t promotion_key \t payment_method_key \t total_sales_qty \t avg_regular_price \t avg_unit_cost \t avg_discount_price \t avg_net_price \t total_extended_discount \t total_extended_sales \t total_extended_cost \t total_extended_profit \t avg_margin
20240101 \t 10 \t 19 \t 38 \t 1 \t 6 \t 2 \t 42000.00 \t 41400.00 \t 0.00 \t 42000.00 \t 0.00 \t 84000.00 \t 82800.00 \t 1200.00 \t 0.0100
```

---

## 🔧 Technical Details

### Mapper Logic

1. **Auto-detect format** berdasarkan jumlah kolom:
   - `>= 18 columns` → Full SQL Export format
   - `>= 7 columns` → Simple CSV format

2. **Composite Key:**
   - Simple: `date_key|product_id|store_id`
   - Full: `date_key|product_key|store_key|cashier_key|promotion_key|payment_method_key`

3. **Values emitted:**
   - Simple: 4 values (quantity, gross, discount, net)
   - Full: 10 values (all metrics from SQL table)

---

### Reducer Logic

1. **Auto-detect format** berdasarkan jumlah parts:
   - `11 parts` (1 key + 10 values) → Full format
   - `5 parts` (1 key + 4 values) → Simple format

2. **Aggregation:**
   - Simple: SUM all values
   - Full: SUM extended amounts, AVERAGE unit prices & margin

3. **Output:**
   - Simple: 7 columns
   - Full: 16 columns

---

### Import Command Logic

1. **Auto-detect format** berdasarkan jumlah TSV columns:
   - `16 columns` → Full format (direct mapping)
   - `7 columns` → Simple format (calculate missing fields)

2. **Missing field handling:**
   - Simple format: Calculate `unit_cost`, `unit_price`, etc. from aggregated data
   - Full format: Use values as-is from TSV

---

## 🚀 Usage Examples

### Example 1: Simple CSV Processing

```bash
# 1. Upload simple CSV via web UI
# File: sales_sample_large.csv (8 columns)

# 2. Run MapReduce
cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts
sudo bash run_etl_root.sh

# 3. Import hasil
php artisan hadoop:import sales_aggregated_20251120_HHMMSS.tsv
```

**Expected:** Data imported dengan default cashier_key=1, promotion_key=1

---

### Example 2: Full SQL Export Re-processing

```bash
# 1. Export dari MySQL
php artisan export:sqlcsv
# Output: storage/app/export/retail_sales_fact.csv (18 columns)

# 2. Copy ke Hadoop input
cp storage/app/export/retail_sales_fact.csv storage/hadoop/input/

# 3. Run MapReduce
cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts
sudo bash run_etl_root.sh

# 4. Import hasil
php artisan hadoop:import sales_aggregated_20251120_HHMMSS.tsv
```

**Expected:** Data imported dengan semua field lengkap (cashier, promotion, payment method preserved)

---

## 📋 Validation

### Test Simple Format
```bash
# Create test file
echo "transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount" > test_simple.csv
echo "1,2024-01-15,101,1,5,2,50000,5000" >> test_simple.csv

# Test mapper
cat test_simple.csv | python3 mapper.py
# Expected: 20240115|101|1	2	100000.00	5000.00	95000.00
```

### Test Full Format
```bash
# Use exported CSV
head -2 storage/app/export/retail_sales_fact.csv | python3 mapper.py
# Expected: 20240101|10|19|38|1|6	2	42000.00	41400.00	0.00	42000.00	0.00	84000.00	82800.00	1200.00	0.0100
```

---

## ⚠️ Important Notes

1. **File naming:** Reducer output selalu bernama `sales_aggregated_YYYYMMDD_HHMMSS.tsv`

2. **Aggregation key:**
   - Simple: Aggregate by date+product+store (3 dimensions)
   - Full: Aggregate by date+product+store+cashier+promotion+payment (6 dimensions)

3. **Data loss prevention:**
   - Full format preserves ALL original fields
   - Simple format uses defaults for missing fields

4. **Performance:**
   - Full format: Lebih detail, file lebih besar
   - Simple format: Lebih cepat, file lebih kecil

---

## 🐛 Troubleshooting

### Error: "File not found"
**Cause:** TSV file tidak ada di `storage/hadoop/processed/`
**Solution:** Cek output MapReduce, pastikan file ter-generate

### Error: "Column count mismatch"
**Cause:** Format TSV tidak sesuai ekspektasi
**Solution:** Cek jumlah kolom di TSV, harus 7 atau 16

### Error: "Foreign key constraint"
**Cause:** product_key/store_key tidak ada di dimension table
**Solution:** Foreign key checks sudah disabled, seharusnya tidak terjadi

---

**Updated:** 2025-11-20
**Version:** 2.0 (Dual Format Support)
