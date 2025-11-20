# Hadoop ETL Pipeline untuk Data Warehouse

## 📁 Struktur Folder

```
storage/hadoop/
├── input/          # CSV mentah dari toko
├── processed/      # Hasil MapReduce (TSV)
├── scripts/        # Python mapper/reducer + bash orchestration
└── README.md       # Dokumentasi ini
```

## 🚀 Cara Menggunakan

### 1. Persiapan Data CSV

Letakkan file CSV transaksi di folder `input/` dengan format:
```csv
transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount
1,2024-01-15,101,1,5,2,50000,5000
```

### 2. Jalankan Hadoop ETL (di WSL)

```bash
# Masuk ke WSL
wsl

# Navigasi ke folder scripts
cd /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts

# Jalankan pipeline
bash run_etl.sh
```

Pipeline akan:
- ✅ Start Hadoop services (jika belum running)
- ✅ Upload CSV ke HDFS
- ✅ Jalankan MapReduce job
- ✅ Download hasil ke folder `processed/`
- ✅ Preview hasil

### 3. Import ke MySQL (di Windows)

```powershell
# Di terminal Windows/PowerShell
cd c:\laragon\www\Data-Warehouse

# Import hasil Hadoop ke database
php artisan hadoop:import sales_aggregated_20251119_231500.tsv
```

## 📊 Output Format

Hasil MapReduce (TSV):
```
date_key    product_id  store_id  quantity  gross_amount  discount  net_amount
20240115    101         1         5         250000.00     12500.00  237500.00
```

## 🔧 Troubleshooting

### Hadoop service tidak jalan
```bash
# Start manual
/usr/local/hadoop/sbin/start-dfs.sh

# Cek status
jps
```

### Permission denied pada scripts
```bash
chmod +x /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts/*.sh
chmod +x /mnt/c/laragon/www/Data-Warehouse/storage/hadoop/scripts/*.py
```

### HDFS safe mode
```bash
hdfs dfsadmin -safemode leave
```

## 📈 Monitoring

### Lihat log Hadoop
```bash
tail -f /usr/local/hadoop/logs/hadoop-*-namenode-*.log
```

### Cek HDFS usage
```bash
hdfs dfs -df -h
hdfs dfs -du -h /user/datawarehouse
```

### Web UI
- NameNode: http://localhost:9870
- ResourceManager: http://localhost:8088

## 🎯 Optimasi

### Untuk dataset besar (>1GB):
1. Increase heap size di `hadoop-env.sh`:
   ```bash
   export HADOOP_HEAPSIZE=2048
   ```

2. Tune MapReduce parameters:
   ```bash
   -D mapreduce.job.reduces=4
   -D mapreduce.map.memory.mb=2048
   ```

3. Gunakan compression:
   ```bash
   -D mapreduce.output.fileoutputformat.compress=true
   ```

## 📚 Referensi

- [Hadoop Streaming](https://hadoop.apache.org/docs/stable/hadoop-streaming/HadoopStreaming.html)
- [HDFS Commands](https://hadoop.apache.org/docs/stable/hadoop-project-dist/hadoop-common/FileSystemShell.html)
- [MapReduce Tutorial](https://hadoop.apache.org/docs/stable/hadoop-mapreduce-client/hadoop-mapreduce-client-core/MapReduceTutorial.html)
