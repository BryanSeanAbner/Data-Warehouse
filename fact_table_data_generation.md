* date_key
* product_key
* store_key
* cashier_key
* promotion_key
* payment_method_key
* transaction_id
** 1, 2, 3, ...
* sales_quantity
* regular_unit_price
* discount_unit_price
* net_unit_price
* extended_discount_amount
* extended_sales_amount
* extended_cost_amount
* extended_gross_profit_amount
* extended_gross_margin_amount

def get_count_transaction(date_key: int) -> int:
    count_transaction = randint(0, 20)
    if is_holiday(date_key):
        count_transaction += randint(3, 6)
    return count_transaction

def get_count_unique_products(date_key: int) -> int:
    count_unique_products = randint(1, 10)
    if is_holiday(date_key):
        count_unique_products += randint(1, 3)
    return count_unique_products

def get_product_key() -> int:
    return random_int(1, 50)

def get_store_cashier_key() -> tuple[int, int]:
    cashier = random_int(1, 40)
    store = (i - 1) // 2 + 1
    return tuple(store, cashier)

def get_promotion_key(product_key: int, date_key: int) -> int:
    pass

def get_payment_method_key(store_key: int) -> int:
    pass

def get_sales_quantity(product_key: int, promotion_key: int) -> int:
    if promotion_key.promotion_code != "NA":
        return random_int(1, 7)
    else:
        return random_int(1, 4)

def get_regular_unit_price(product_key: int) -> float:
    regular_unit_price = 12000
    if product_key < 10:
        regular_unit_price *= 2.5
    elif product_key < 20:
        regular_unit_price *= 3.5
    elif product_key < 30:
        regular_unit_price += 14.000
        if product_key % 2 == 0:
            regular_unit_price *= 1.1
    elif product_key < 40:
        if product_key % 2 == 1:
            regular_unit_price *= 0.7
        else:
            regular_unit_price = 27500
            if product_key > 46:
                regular_unit_price *= 1.2
    return regular_unit_price

def get_unit_cost(product_key: int) -> float:
    if product_key < 24:
        if product_key % 2 == 0:
            return - 15600 + get_regular_unit_price(product_key) * 1.3
        else:
            return get_regular_unit_price(product_key) * 0.9 + 3200
    elif product_key < 37:
        return get_regular_unit_price(product_key) * 1.2 - 4000
    else:
        return get_regular_unit_price(product_key) * 0.7
    
def get_discount_unit_price(product_key: int, promotion_key: int) -> float:
    if promotion_key.promotion_code == "NA":
        return 0
    else:
        return 0.12 * get_regular_unit_price(product_key)

def get_net_unit_price(regular_unit_price: float, discount_unit_price: float) -> float:
    return regular_unit_price - discount_unit_price
    
def get_extended_discount_amount(sales_quantity: int, discount_unit_price: float) -> float:
    return sales_quantity * discount_unit_price

def get_extended_sales_amount(sales_quantity: int, net_unit_price: float) -> float:
    return sales_quantity * net_unit_price

def get_extended_cost_amount(sales_quantity: int, unit_cost: float) -> float:
    return sales_quantity * unit_cost

def get_extended_gross_profit_amount(extended_cost_amount: float) -> float:
    return extended_sales_amount - extended_cost_amount

for date_key in date_keys:
    count_transaction = get_count_transaction(date_key)
    for i_transaction in range(count_transaction):
        count_unique_products = get_count_unique_products(date_key)
        for i_unique_product in range(count_unique_products):
            product_key = get_product_key()
            store_key, cashier_key = get_store_cashier_key()
            promotion_key = get_promotion_key(product_key, date_key)
            payment_method_key = get_payment_method_key(store_key)
            sales_quantity = get_sales_quantity(product_key, promotion_key)
            insert_to_db(
                date_key,
                product_key,
                store_key,
                cashier_key,
                promotion_key,
                payment_method_key,
                i_transaction,
                sales_quantity,
                regular_unit_price,
                unit_cost,
                discount_unit_price,
                net_unit_price,
                extended_discount_amount,
                extended_sales_amount,
                extended_cost_amount,
                extended_gross_profit_amount
            )
