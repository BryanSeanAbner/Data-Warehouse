#!/usr/bin/env python3
"""
Hadoop Reducer: Aggregate transaksi per composite key
Supports 2 formats:
1. Simple: date_key|product_id|store_id
2. Full: date_key|product_key|store_key|cashier_key|promotion_key|payment_method_key
"""
import sys

def main():
    current_key = None
    
    # Aggregation variables for simple format
    total_quantity = 0
    total_gross = 0.0
    total_discount = 0.0
    total_net = 0.0
    
    # Aggregation variables for full format
    total_sales_quantity = 0
    sum_regular_unit_price = 0.0
    sum_unit_cost = 0.0
    sum_discount_unit_price = 0.0
    sum_net_unit_price = 0.0
    total_extended_discount = 0.0
    total_extended_sales = 0.0
    total_extended_cost = 0.0
    total_extended_profit = 0.0
    sum_margin = 0.0
    count = 0
    
    for line in sys.stdin:
        try:
            # Parse input dari mapper
            parts = line.strip().split('\t')
            
            if len(parts) < 5:
                continue
            
            composite_key = parts[0]
            
            # Detect format by number of parts
            if len(parts) == 11:
                # Full format: 1 key + 10 values
                # key: date_key|product_key|store_key|cashier_key|promotion_key|payment_method_key
                # values: sales_quantity, regular_unit_price, unit_cost, discount_unit_price, net_unit_price,
                #         extended_discount_amount, extended_sales_amount, extended_cost_amount, 
                #         extended_gross_profit_amount, extended_gross_margin_amount
                
                if current_key == composite_key:
                    # Accumulate
                    total_sales_quantity += int(parts[1])
                    sum_regular_unit_price += float(parts[2])
                    sum_unit_cost += float(parts[3])
                    sum_discount_unit_price += float(parts[4])
                    sum_net_unit_price += float(parts[5])
                    total_extended_discount += float(parts[6])
                    total_extended_sales += float(parts[7])
                    total_extended_cost += float(parts[8])
                    total_extended_profit += float(parts[9])
                    sum_margin += float(parts[10])
                    count += 1
                else:
                    # Emit previous key
                    if current_key and count > 0:
                        key_parts = current_key.split('|')
                        avg_regular_price = sum_regular_unit_price / count
                        avg_unit_cost = sum_unit_cost / count
                        avg_discount_price = sum_discount_unit_price / count
                        avg_net_price = sum_net_unit_price / count
                        avg_margin = sum_margin / count
                        
                        output = f"{key_parts[0]}\t{key_parts[1]}\t{key_parts[2]}\t{key_parts[3]}\t{key_parts[4]}\t{key_parts[5]}\t"
                        output += f"{total_sales_quantity}\t{avg_regular_price:.2f}\t{avg_unit_cost:.2f}\t{avg_discount_price:.2f}\t{avg_net_price:.2f}\t"
                        output += f"{total_extended_discount:.2f}\t{total_extended_sales:.2f}\t{total_extended_cost:.2f}\t{total_extended_profit:.2f}\t{avg_margin:.4f}"
                        print(output)
                    
                    # Reset for new key
                    current_key = composite_key
                    total_sales_quantity = int(parts[1])
                    sum_regular_unit_price = float(parts[2])
                    sum_unit_cost = float(parts[3])
                    sum_discount_unit_price = float(parts[4])
                    sum_net_unit_price = float(parts[5])
                    total_extended_discount = float(parts[6])
                    total_extended_sales = float(parts[7])
                    total_extended_cost = float(parts[8])
                    total_extended_profit = float(parts[9])
                    sum_margin = float(parts[10])
                    count = 1
                    
            elif len(parts) == 5:
                # Simple format: 1 key + 4 values
                # key: date_key|product_id|store_id
                # values: quantity, gross_amount, discount, net_amount
                
                quantity = int(parts[1])
                gross_amount = float(parts[2])
                discount = float(parts[3])
                net_amount = float(parts[4])
                
                if current_key == composite_key:
                    total_quantity += quantity
                    total_gross += gross_amount
                    total_discount += discount
                    total_net += net_amount
                else:
                    # Emit previous key
                    if current_key:
                        key_parts = current_key.split('|')
                        print(f"{key_parts[0]}\t{key_parts[1]}\t{key_parts[2]}\t{total_quantity}\t{total_gross:.2f}\t{total_discount:.2f}\t{total_net:.2f}")
                    
                    # Reset for new key
                    current_key = composite_key
                    total_quantity = quantity
                    total_gross = gross_amount
                    total_discount = discount
                    total_net = net_amount
                    count = 0  # Mark as simple format
                
        except (ValueError, IndexError) as e:
            sys.stderr.write(f"Reducer Error: {str(e)} | Line: {line}\n")
            continue
    
    # Emit last key
    if current_key:
        if count > 0:
            # Full format
            key_parts = current_key.split('|')
            avg_regular_price = sum_regular_unit_price / count
            avg_unit_cost = sum_unit_cost / count
            avg_discount_price = sum_discount_unit_price / count
            avg_net_price = sum_net_unit_price / count
            avg_margin = sum_margin / count
            
            output = f"{key_parts[0]}\t{key_parts[1]}\t{key_parts[2]}\t{key_parts[3]}\t{key_parts[4]}\t{key_parts[5]}\t"
            output += f"{total_sales_quantity}\t{avg_regular_price:.2f}\t{avg_unit_cost:.2f}\t{avg_discount_price:.2f}\t{avg_net_price:.2f}\t"
            output += f"{total_extended_discount:.2f}\t{total_extended_sales:.2f}\t{total_extended_cost:.2f}\t{total_extended_profit:.2f}\t{avg_margin:.4f}"
            print(output)
        else:
            # Simple format
            key_parts = current_key.split('|')
            print(f"{key_parts[0]}\t{key_parts[1]}\t{key_parts[2]}\t{total_quantity}\t{total_gross:.2f}\t{total_discount:.2f}\t{total_net:.2f}")

if __name__ == '__main__':
    main()
