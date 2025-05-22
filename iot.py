import serial
import oracledb
from datetime import datetime

# ✅ Enable Thick Mode with your Instant Client path
oracledb.init_oracle_client(lib_dir=r"C:\instantclient_21_17")

# Oracle DB Connection
DB_USER = "bibash"
DB_PASS = "bibash"
DB_CONN = "localhost/xe"  # Use XE for Oracle Express Edition

def get_serial_uid():
    try:
        arduino = serial.Serial('COM7', 9600, timeout=10)
        print("Waiting for RFID UID scan...")
        
        while True:
            line = arduino.readline().decode('utf-8').strip()
            if line.startswith("RFID Tag UID:"):
                uid = line.replace("RFID Tag UID:", "").strip()
                arduino.close()
                return uid
    except serial.SerialException as e:
        print(f"Error reading serial port: {e}")
        return None

def check_uid_exists(cursor, uid):
    cursor.execute("SELECT COUNT(*) FROM PRODUCT WHERE PRODUCT_NAME = :1", (uid,))
    result = cursor.fetchone()
    return result[0] > 0

def insert_product(cursor, uid):
    print("Enter product details:")
    product_name = input("Product Name: ")
    price = float(input("Price: "))
    min_order = int(input("Minimum Order: "))
    max_order = int(input("Maximum Order: "))
    category = input("Category: ")
    stock = int(input("Stock: "))
    allergy_warning = input("Allergy Warning (optional): ")
    shop_id = int(input("Shop ID (FK1_SHOP_ID): "))
    category_id = int(input("Category ID (FK2_CATEGORY_ID): "))

    image_path = input("Enter path to product image file (e.g. C:\\images\\item.png): ").strip().strip('"')
    try:
        with open(image_path, 'rb') as f:
            image_data = f.read()
    except Exception as e:
        print(f"❌ Failed to read image file: {e}")
        return

    # ✅ Do not include PRODUCT_ID (trigger will handle it)
    cursor.execute("""
        INSERT INTO PRODUCT (
            PRODUCT_NAME,
            PRICE,
            PRODUCT_IMAGE,
            MINIMUM_ORDER,
            MAXIMUM_ORDER,
            CATEGORY,
            STOCK,
            ALLERGY_WARNING,
            FK1_SHOP_ID,
            FK2_CATEGORY_ID
        ) VALUES (
            :product_name,
            :price,
            :product_image,
            :min_order,
            :max_order,
            :category,
            :stock,
            :allergy_warning,
            :shop_id,
            :category_id
        )
    """, {
        'product_name': product_name,
        'price': price,
        'product_image': image_data,
        'min_order': min_order,
        'max_order': max_order,
        'category': category,
        'stock': stock,
        'allergy_warning': allergy_warning if allergy_warning else None,
        'shop_id': shop_id,
        'category_id': category_id
    })

def main():
    try:
        conn = oracledb.connect(user=DB_USER, password=DB_PASS, dsn=DB_CONN)
        cursor = conn.cursor()
        print("✅ Connected to Oracle DB successfully.")
    except oracledb.DatabaseError as e:
        print(f"❌ Database connection error: {e}")
        return

    try:
        while True:
            uid = get_serial_uid()
            if not uid:
                print("⚠️ No UID read. Try again.")
                continue

            print(f"📟 Scanned UID (used as Product Name for now): {uid}")

            if check_uid_exists(cursor, uid):
                print("⚠️  Item already exists. Use a different product name.")
            else:
                print("✅ New item. Let's add the product.")
                insert_product(cursor, uid)
                conn.commit()
                print("🎉 Product inserted successfully.")

            cont = input("Scan another? (y/n): ")
            if cont.lower() != 'y':
                break
    except KeyboardInterrupt:
        print("\n🛑 Process interrupted by user.")
    finally:
        cursor.close()
        conn.close()
        print("🔒 Connection closed.")

if __name__ == "__main__":
    main()
