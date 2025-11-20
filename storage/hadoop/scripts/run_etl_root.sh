#!/bin/bash
# Hadoop ETL Pipeline untuk Data Warehouse (Root Mode)
# Usage: sudo bash run_etl_root.sh

set -e  # Exit on error

# Set environment variables untuk root
export HDFS_NAMENODE_USER=root
export HDFS_DATANODE_USER=root
export HDFS_SECONDARYNAMENODE_USER=root
export YARN_RESOURCEMANAGER_USER=root
export YARN_NODEMANAGER_USER=root

# Konfigurasi
HADOOP_HOME=/usr/local/hadoop
PROJECT_PATH=/mnt/c/laragon/www/Data-Warehouse
INPUT_LOCAL=$PROJECT_PATH/storage/hadoop/input
OUTPUT_LOCAL=$PROJECT_PATH/storage/hadoop/processed
SCRIPTS=$PROJECT_PATH/storage/hadoop/scripts

# HDFS paths
HDFS_INPUT=/user/datawarehouse/input
HDFS_OUTPUT=/user/datawarehouse/output/$(date +%Y%m%d_%H%M%S)

echo "========================================="
echo "🚀 Hadoop ETL Pipeline Started (Root Mode)"
echo "========================================="

# 1. Cek Hadoop service
echo "📡 Checking Hadoop services..."
if ! jps | grep -q "NameNode"; then
    echo "⚠️  Starting Hadoop services..."
    $HADOOP_HOME/sbin/start-dfs.sh
    sleep 5
fi

# 2. Buat direktori HDFS jika belum ada
echo "📁 Creating HDFS directories..."
$HADOOP_HOME/bin/hdfs dfs -mkdir -p $HDFS_INPUT
$HADOOP_HOME/bin/hdfs dfs -mkdir -p /user/datawarehouse/output

# 3. Upload CSV ke HDFS
echo "📤 Uploading CSV files to HDFS..."
$HADOOP_HOME/bin/hdfs dfs -rm -r -f $HDFS_INPUT/*  # Clean old data
$HADOOP_HOME/bin/hdfs dfs -put $INPUT_LOCAL/*.csv $HDFS_INPUT/

# Verifikasi upload
FILE_COUNT=$($HADOOP_HOME/bin/hdfs dfs -ls $HDFS_INPUT/*.csv | wc -l)
echo "✅ Uploaded $FILE_COUNT CSV files"

# 4. Jalankan MapReduce Job
echo "⚙️  Running Hadoop MapReduce job..."
$HADOOP_HOME/bin/hadoop jar $HADOOP_HOME/share/hadoop/tools/lib/hadoop-streaming-*.jar \
  -input $HDFS_INPUT/*.csv \
  -output $HDFS_OUTPUT \
  -mapper "python3 mapper.py" \
  -reducer "python3 reducer.py" \
  -file $SCRIPTS/mapper.py \
  -file $SCRIPTS/reducer.py

# 5. Download hasil ke local
echo "📥 Downloading results from HDFS..."
rm -rf $OUTPUT_LOCAL/*  # Clean old results
$HADOOP_HOME/bin/hdfs dfs -get $HDFS_OUTPUT/part-* $OUTPUT_LOCAL/

# Rename file untuk mudah dikenali
RESULT_FILE=$OUTPUT_LOCAL/sales_aggregated_$(date +%Y%m%d_%H%M%S).tsv
mv $OUTPUT_LOCAL/part-00000 $RESULT_FILE 2>/dev/null || true

echo "✅ Results saved to: $RESULT_FILE"

# 6. Preview hasil (5 baris pertama)
echo ""
echo "📊 Preview hasil (5 baris pertama):"
echo "date_key | product_id | store_id | qty | gross | discount | net"
echo "-------------------------------------------------------------------"
head -5 $RESULT_FILE

# 7. Statistik
TOTAL_ROWS=$(wc -l < $RESULT_FILE)
echo ""
echo "📈 Statistics:"
echo "   Total aggregated rows: $TOTAL_ROWS"

echo ""
echo "========================================="
echo "✅ ETL Pipeline Completed Successfully!"
echo "========================================="
echo ""
echo "Next step: Import to MySQL dengan command:"
echo "  cd $PROJECT_PATH"
echo "  php artisan hadoop:import $(basename $RESULT_FILE)"
