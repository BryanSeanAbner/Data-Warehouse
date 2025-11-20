#!/bin/bash
# Backup & Restore Script untuk retail_sales_fact

# Backup database
backup_db() {
    TIMESTAMP=$(date +%Y%m%d_%H%M%S)
    BACKUP_FILE="backup_retail_sales_fact_${TIMESTAMP}.sql"
    
    echo "📦 Creating backup: ${BACKUP_FILE}"
    mysqldump -u root -p data_warehouse retail_sales_fact > "storage/backups/${BACKUP_FILE}"
    
    if [ $? -eq 0 ]; then
        echo "✅ Backup created successfully!"
        echo "📁 Location: storage/backups/${BACKUP_FILE}"
    else
        echo "❌ Backup failed!"
        exit 1
    fi
}

# Restore database
restore_db() {
    if [ -z "$1" ]; then
        echo "❌ Usage: ./backup_restore.sh restore <backup_file>"
        echo "Available backups:"
        ls -lh storage/backups/*.sql
        exit 1
    fi
    
    BACKUP_FILE=$1
    
    if [ ! -f "$BACKUP_FILE" ]; then
        echo "❌ Backup file not found: ${BACKUP_FILE}"
        exit 1
    fi
    
    echo "⚠️  WARNING: This will replace current data!"
    read -p "Continue? (y/n): " confirm
    
    if [ "$confirm" = "y" ]; then
        echo "📥 Restoring from: ${BACKUP_FILE}"
        mysql -u root -p data_warehouse < "$BACKUP_FILE"
        
        if [ $? -eq 0 ]; then
            echo "✅ Restore completed successfully!"
        else
            echo "❌ Restore failed!"
            exit 1
        fi
    else
        echo "❌ Restore cancelled"
    fi
}

# Main
case "$1" in
    backup)
        backup_db
        ;;
    restore)
        restore_db "$2"
        ;;
    *)
        echo "Usage:"
        echo "  ./backup_restore.sh backup              # Create backup"
        echo "  ./backup_restore.sh restore <file>      # Restore from backup"
        exit 1
        ;;
esac
