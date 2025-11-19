# 🚀 Hadoop ETL Integration Guide

## Overview

Integrasi lengkap Hadoop MapReduce dengan Laravel Data Warehouse untuk ETL pipeline:

```
CSV Files (Toko) → Hadoop/HDFS (WSL) → MapReduce → Transform → MySQL (Laravel)
```

---

## 📋 Fitur

### 1. **Upload CSV** 
- Upload file CSV transaksi toko via web interface
- Format: `transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount`
- File disimpan di `storage/hadoop/input/`

### 2. **Hadoop MapReduce Processing**
- Jalankan MapReduce job di WSL untuk aggregate data
- Mapper: Parse CSV dan emit key-value pairs
- Reducer: Aggregate per date+product+store
- Output: TSV file di `storage/hadoop/processed/`

### 3. **Import to MySQL**
- Import hasil MapReduce (TSV) ke tabel `retail_sales_fact`
- Batch insert 500 rows per query
- Foreign key checks disabled sementara

### 4. **Export SQL to CSV**
- Export data dari MySQL ke CSV untuk re-processing
- Output: `storage/app/export/retail_sales_fact.csv`

---

## 🎯 Cara Menggunakan

### Step 1: Akses Hadoop ETL Management

Buka browser dan navigasi ke:
```
https://data-warehouse.test/hadoop
```

Atau klik **"Hadoop ETL"** di sidebar dashboard.

### Step 2: Upload CSV File

1. Klik **"Upload CSV File"**
2. Pilih file CSV transaksi
3. Klik **"Upload CSV"**
4. File akan muncul di list **"Input Files (CSV)"**

### Step 3: Run Hadoop MapReduce (WSL)

1. Buka WSL terminal
2. Jalankan command:
   ```bash
   cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts
   sudo bash run_etl_root.sh
   ```
3. Tunggu hingga selesai (akan muncul preview hasil)
4. File TSV akan muncul di **"Processed Files (TSV)"**

### Step 4: Import TSV to MySQL

1. Klik icon **upload (↑)** di sebelah file TSV
2. Data akan diimport ke tabel `retail_sales_fact`
3. Cek statistik di **"Total Imported"**

---

## 📁 Struktur File

```
storage/hadoop/
├── input/              # CSV files (uploaded via web)
│   └── sales_2024.csv
├── processed/          # TSV files (MapReduce output)
│   └── sales_aggregated_20251119_232746.tsv
├── scripts/
│   ├── mapper.py       # Python mapper
│   ├── reducer.py      # Python reducer
│   ├── run_etl.sh      # User mode script
│   └── run_etl_root.sh # Root mode script
└── README.md           # Dokumentasi Hadoop

storage/app/export/     # SQL export output
└── retail_sales_fact.csv
```

---

## 🔧 Artisan Commands

### Import Hadoop TSV
```bash
php artisan hadoop:import sales_aggregated_20251119_232746.tsv
```

### Export SQL to CSV
```bash
php artisan export:sqlcsv
```

---

## 🌐 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/hadoop` | Hadoop ETL dashboard |
| POST | `/hadoop/upload` | Upload CSV file |
| POST | `/hadoop/import` | Import TSV to MySQL |
| POST | `/hadoop/export` | Export SQL to CSV |
| DELETE | `/hadoop/delete` | Delete file (input/processed) |

---

## 📊 Data Flow

### 1. CSV Input Format
```csv
transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount
1,2024-01-15,101,1,5,2,50000,5000
2,2024-01-15,102,1,5,1,75000,0
```

### 2. MapReduce Output (TSV)
```
date_key    product_id  store_id  qty  gross        discount    net
20240115    101         1         2    100000.00    5000.00     95000.00
20240115    102         1         1    75000.00     0.00        75000.00
```

### 3. MySQL Table (retail_sales_fact)
```sql
SELECT 
    date_key, 
    product_key, 
    store_key, 
    sales_quantity, 
    extended_sales_amount 
FROM retail_sales_fact 
LIMIT 5;
```

---

## ⚙️ Konfigurasi

### Hadoop Environment (WSL)
```bash
export HADOOP_HOME=/usr/local/hadoop
export PATH=$PATH:$HADOOP_HOME/bin:$HADOOP_HOME/sbin
```

### Laravel .env
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=data_warehouse
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🐛 Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"
**Solusi:** Ubah `DB_HOST` dari `127.0.0.1` ke `localhost` di `.env`

### Error: "Column 'created_at' not found"
**Solusi:** Tabel tidak punya timestamps, sudah difix di `ImportHadoopData.php`

### Error: "Foreign key constraint fails"
**Solusi:** Foreign key checks disabled sementara saat import

### MapReduce: "ssh: Connection refused"
**Solusi:** Normal untuk standalone mode, tidak perlu SSH

---

## 📈 Monitoring

### Cek Jumlah Data Imported
```bash
php artisan tinker --execute="DB::table('retail_sales_fact')->count();"
```

### Cek Last Import
```bash
php artisan tinker --execute="DB::table('retail_sales_fact')->latest('date_key')->first();"
```

### Hadoop Job History
```bash
# Di WSL
hdfs dfs -ls /user/datawarehouse/output/
```

---

## 🎓 Best Practices

1. **Batch Size**: Import 500 rows per batch untuk performa optimal
2. **File Naming**: Gunakan timestamp di nama file untuk tracking
3. **Cleanup**: Hapus file lama secara berkala untuk hemat storage
4. **Backup**: Export SQL to CSV sebelum re-import data baru
5. **Validation**: Cek data di dashboard setelah import

---

## 📚 Referensi

- [Hadoop Streaming Documentation](https://hadoop.apache.org/docs/stable/hadoop-streaming/HadoopStreaming.html)
- [Laravel Database: Query Builder](https://laravel.com/docs/database)
- [WSL Integration Guide](https://learn.microsoft.com/en-us/windows/wsl/)

---

**Happy Data Warehousing! 🎉**
