#!/usr/bin/env python3
"""
Hadoop Mapper: Parse CSV transaksi toko dan emit key-value pairs
Input: transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount
Output: date_key|product_id|store_id \t quantity \t amount
"""
import sys
import csv
from datetime import datetime

def main():
    reader = csv.reader(sys.stdin)
    
    for row in reader:
        try:
            # Skip header
            if row[0] == 'transaction_id' or not row[0].isdigit():
                continue
            
            # Parse CSV columns
            transaction_id = row[0]
            date_str = row[1]           # '2024-01-15'
            product_id = row[2]
            store_id = row[3]
            cashier_id = row[4] if len(row) > 4 else '0'
            quantity = int(row[5] if len(row) > 5 else row[4])
            unit_price = float(row[6] if len(row) > 6 else row[5])
            discount = float(row[7]) if len(row) > 7 else 0.0
            
            # Convert date ke date_key (YYYYMMDD)
            date_obj = datetime.strptime(date_str, '%Y-%m-%d')
            date_key = int(date_obj.strftime('%Y%m%d'))
            
            # Calculate amount
            gross_amount = quantity * unit_price
            net_amount = gross_amount - discount
            
            # Emit: composite_key \t quantity \t gross_amount \t discount \t net_amount
            composite_key = f"{date_key}|{product_id}|{store_id}"
            print(f"{composite_key}\t{quantity}\t{gross_amount:.2f}\t{discount:.2f}\t{net_amount:.2f}")
            
        except (ValueError, IndexError) as e:
            # Log error ke stderr (Hadoop akan capture)
            sys.stderr.write(f"Mapper Error: {str(e)} | Row: {row}\n")
            continue

if __name__ == '__main__':
    main()
