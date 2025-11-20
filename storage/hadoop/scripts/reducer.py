#!/usr/bin/env python3
"""
Hadoop Reducer: Aggregate transaksi per date_key + product + store
Input: date_key|product_id|store_id \t quantity \t gross_amount \t discount \t net_amount
Output: date_key \t product_id \t store_id \t total_qty \t total_gross \t total_discount \t total_net
"""
import sys

def main():
    current_key = None
    total_quantity = 0
    total_gross = 0.0
    total_discount = 0.0
    total_net = 0.0
    
    for line in sys.stdin:
        try:
            # Parse input dari mapper
            parts = line.strip().split('\t')
            if len(parts) != 5:
                continue
                
            composite_key = parts[0]
            quantity = int(parts[1])
            gross_amount = float(parts[2])
            discount = float(parts[3])
            net_amount = float(parts[4])
            
            # Jika key sama, akumulasi
            if current_key == composite_key:
                total_quantity += quantity
                total_gross += gross_amount
                total_discount += discount
                total_net += net_amount
            else:
                # Emit hasil agregasi untuk key sebelumnya
                if current_key:
                    date_key, product_id, store_id = current_key.split('|')
                    print(f"{date_key}\t{product_id}\t{store_id}\t{total_quantity}\t{total_gross:.2f}\t{total_discount:.2f}\t{total_net:.2f}")
                
                # Reset untuk key baru
                current_key = composite_key
                total_quantity = quantity
                total_gross = gross_amount
                total_discount = discount
                total_net = net_amount
                
        except (ValueError, IndexError) as e:
            sys.stderr.write(f"Reducer Error: {str(e)} | Line: {line}\n")
            continue
    
    # Emit hasil terakhir
    if current_key:
        date_key, product_id, store_id = current_key.split('|')
        print(f"{date_key}\t{product_id}\t{store_id}\t{total_quantity}\t{total_gross:.2f}\t{total_discount:.2f}\t{total_net:.2f}")

if __name__ == '__main__':
    main()
