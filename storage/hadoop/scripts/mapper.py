#!/usr/bin/env python3
"""
Hadoop Mapper: Parse CSV transaksi toko dan emit key-value pairs
Supports 2 formats:
1. Simple: transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount
2. Full SQL Export: id,date_key,product_key,store_key,cashier_key,promotion_key,payment_method_key,transaction_id,sales_quantity,regular_unit_price,unit_cost,discount_unit_price,net_unit_price,extended_discount_amount,extended_sales_amount,extended_cost_amount,extended_gross_profit_amount,extended_gross_margin_amount
"""
import sys
import csv
from datetime import datetime

def main():
    reader = csv.reader(sys.stdin)
    
    for row in reader:
        try:
            # Skip header or empty rows
            if not row or not row[0] or row[0] in ['transaction_id', 'id']:
                continue
            
            # Detect format by number of columns
            num_cols = len(row)
            
            if num_cols >= 18:
                # Format 2: Full SQL Export (18 columns)
                # id,date_key,product_key,store_key,cashier_key,promotion_key,payment_method_key,transaction_id,
                # sales_quantity,regular_unit_price,unit_cost,discount_unit_price,net_unit_price,
                # extended_discount_amount,extended_sales_amount,extended_cost_amount,extended_gross_profit_amount,extended_gross_margin_amount
                
                date_key = int(row[1])
                product_key = int(row[2])
                store_key = int(row[3])
                cashier_key = int(row[4])
                promotion_key = int(row[5])
                payment_method_key = int(row[6])
                transaction_id = int(row[7])
                sales_quantity = int(row[8])
                regular_unit_price = float(row[9])
                unit_cost = float(row[10])
                discount_unit_price = float(row[11])
                net_unit_price = float(row[12])
                extended_discount_amount = float(row[13])
                extended_sales_amount = float(row[14])
                extended_cost_amount = float(row[15])
                extended_gross_profit_amount = float(row[16])
                extended_gross_margin_amount = float(row[17])
                
                # Emit all data for aggregation
                composite_key = f"{date_key}|{product_key}|{store_key}|{cashier_key}|{promotion_key}|{payment_method_key}"
                values = f"{sales_quantity}\t{regular_unit_price:.2f}\t{unit_cost:.2f}\t{discount_unit_price:.2f}\t{net_unit_price:.2f}\t{extended_discount_amount:.2f}\t{extended_sales_amount:.2f}\t{extended_cost_amount:.2f}\t{extended_gross_profit_amount:.2f}\t{extended_gross_margin_amount:.4f}"
                print(f"{composite_key}\t{values}")
                
            elif num_cols >= 7:
                # Format 1: Simple CSV (7-8 columns)
                # transaction_id,date,product_id,store_id,cashier_id,quantity,unit_price,discount
                
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
                
                # Calculate amounts
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
