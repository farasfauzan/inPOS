-- =====================================================
-- DDL for inPOS-Laravel Database
-- Database: MySQL
-- ERD v2.0 - dengan Role-Based Access Control
-- =====================================================

-- =====================================================
-- Drop tables if exist (for clean import)
-- =====================================================
DROP TABLE IF EXISTS stock_history CASCADE;
DROP TABLE IF EXISTS transaction_details CASCADE;
DROP TABLE IF EXISTS transactions CASCADE;
DROP TABLE IF EXISTS products CASCADE;
DROP TABLE IF EXISTS categories CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- =====================================================
-- TABLE: users
-- Description: User accounts dengan Role-Based Access Control
-- =====================================================
CREATE TABLE users (
    users_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'PK: ID User',
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Username login (unik)',
    password VARCHAR(255) NOT NULL COMMENT 'Password (bcrypt hashed)',
    full_name VARCHAR(100) NOT NULL COMMENT 'Nama lengkap user',
    role ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir'
        COMMENT 'ROLE: admin=Akses penuh | kasir=Akses POS saja',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- RBAC Role Descriptions:
    -- 'admin'  = Administrator (CRUD products, categories, inventory, reports, POS)
    -- 'kasir'  = Cashier       (POS only - hanya bisa proses transaksi)
    INDEX idx_users_role (role)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: categories
-- Description: Kategori produk (dikelola oleh Admin)
-- =====================================================
CREATE TABLE categories (
    categories_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'PK: ID Kategori',
    name VARCHAR(100) NOT NULL COMMENT 'Nama kategori (Makanan, Minuman, Snack)',
    description TEXT COMMENT 'Deskripsi kategori',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal dibuat',
    created_by INT COMMENT 'FK: User yang membuat (NULL jika sistem)',

    CONSTRAINT fk_categories_created_by FOREIGN KEY (created_by)
        REFERENCES users(users_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: products
-- Description: Produk dengan stok (CRUD oleh Admin)
-- =====================================================
CREATE TABLE products (
    products_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'PK: ID Produk',
    name VARCHAR(100) NOT NULL COMMENT 'Nama produk',
    categories_id INT COMMENT 'FK: Kategori produk',
    price DECIMAL(10, 0) NOT NULL COMMENT 'Harga jual',
    stock INT NOT NULL DEFAULT 0 COMMENT 'Stok saat ini',
    min_stock INT NOT NULL DEFAULT 5 COMMENT 'Batas minimum stok (alert)',
    unit VARCHAR(20) DEFAULT 'pcs' COMMENT 'Satuan (pcs, porsi, gelas)',
    barcode VARCHAR(50) UNIQUE COMMENT 'Barcode produk (opsional)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT COMMENT 'FK: Admin yang membuat produk',

    -- Foreign Key
    CONSTRAINT fk_products_categories FOREIGN KEY (categories_id)
        REFERENCES categories(categories_id) ON DELETE SET NULL,

    CONSTRAINT fk_products_created_by FOREIGN KEY (created_by)
        REFERENCES users(users_id) ON DELETE SET NULL,

    -- Constraint
    CONSTRAINT chk_stock_positive CHECK (stock >= 0),

    -- Indexes
    INDEX idx_products_name (name),
    INDEX idx_products_category (categories_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: transactions
-- Description: Header transaksi penjualan (oleh Kasir atau Admin)
-- =====================================================
CREATE TABLE transactions (
    transactions_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'PK: ID Transaksi',
    users_id INT NOT NULL COMMENT 'FK: User yang memproses transaksi (Kasir/Admin)',
    total_amount DECIMAL(12, 0) NOT NULL COMMENT 'Total harga',
    payment_method ENUM('cash', 'debit', 'qris') NOT NULL COMMENT 'Metode pembayaran',
    amount_paid DECIMAL(12, 0) NOT NULL COMMENT 'Jumlah yang dibayar',
    change_amount DECIMAL(12, 0) NOT NULL COMMENT 'Kembalian',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Tanggal & waktu transaksi',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Relasi ke User (Siapa yang kasir/admin yang proses transaksi)
    CONSTRAINT fk_transactions_users FOREIGN KEY (users_id)
        REFERENCES users(users_id) ON DELETE RESTRICT,

    INDEX idx_transactions_date (transaction_date),
    INDEX idx_transactions_users (users_id),
    INDEX idx_transactions_payment (payment_method)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: transaction_details
-- Description: Detail item transaksi
-- =====================================================
CREATE TABLE transaction_details (
    transaction_details_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'PK: ID Detail',
    transactions_id INT NOT NULL COMMENT 'FK: Header transaksi',
    products_id INT NOT NULL COMMENT 'FK: Produk yang dibeli',
    quantity INT NOT NULL COMMENT 'Jumlah beli',
    price DECIMAL(10, 0) NOT NULL COMMENT 'Harga saat transaksi',
    subtotal DECIMAL(12, 0) NOT NULL COMMENT 'Subtotal (qty x price)',

    -- Relasi ke Transactions (Header)
    CONSTRAINT fk_details_transactions FOREIGN KEY (transactions_id)
        REFERENCES transactions(transactions_id) ON DELETE CASCADE,

    -- Relasi ke Products (Produk yang dijual)
    CONSTRAINT fk_details_products FOREIGN KEY (products_id)
        REFERENCES products(products_id) ON DELETE RESTRICT,

    INDEX idx_details_transactions (transactions_id),
    INDEX idx_details_products (products_id)
) ENGINE=InnoDB;

-- =====================================================
-- TABLE: stock_history
-- Description: Riwayat perubahan stok (oleh Admin via Restock/Adjust)
-- =====================================================
CREATE TABLE stock_history (
    stock_history_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'PK: ID History',
    products_id INT NOT NULL COMMENT 'FK: Produk yang berubah stoknya',
    change_type ENUM('sale', 'restock', 'adjustment') NOT NULL
        COMMENT 'Tipe perubahan: sale=penjualan | restock=tambah stok | adjustment=koreksi',
    quantity_change INT NOT NULL COMMENT 'Jumlah perubahan (+/-)',
    stock_before INT NOT NULL COMMENT 'Stok sebelum perubahan',
    stock_after INT NOT NULL COMMENT 'Stok setelah perubahan',
    note TEXT COMMENT 'Catatan perubahan',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu perubahan',
    changed_by INT COMMENT 'FK: Admin yang merubah stok (NULL jika sistem/official)',

    -- Relasi ke Products
    CONSTRAINT fk_history_products FOREIGN KEY (products_id)
        REFERENCES products(products_id) ON DELETE CASCADE,

    -- Relasi ke Users (Admin yang bertanggung jawab)
    CONSTRAINT fk_history_changed_by FOREIGN KEY (changed_by)
        REFERENCES users(users_id) ON DELETE SET NULL,

    INDEX idx_history_products (products_id),
    INDEX idx_history_type (change_type),
    INDEX idx_history_date (created_at)
) ENGINE=InnoDB;

-- =====================================================
-- RELATIONSHIP SUMMARY
-- =====================================================
/*
+------------------+       +------------------+       +---------------------+
|      USERS       |       |   CATEGORIES     |       |      PRODUCTS       |
+------------------+       +------------------+       +---------------------+
| PK users_id     |<------+ FK created_by    |       | PK products_id      |
|     username    |       | PK categories_id  |<--+   | FK categories_id    |
|     password     |       |     name          |   |   | FK created_by       |
|     full_name   |       |     description   |   |   |     name            |
| ENUM role       |       +------------------+   |   |     price           |
|   ['admin']     |                              |   |     stock           |
|   ['kasir']     |                              |   |     min_stock       |
+------------------+                              |   |     unit            |
| ROLE MAPPING:                                  |   |     barcode         |
| - admin: CRUD all                              |   +---------------------+
| - kasir: POS only                               |           |
+------------------+                              |           | 1:N
        |                                        |           |
        | 1:N                                    |           |
        v                                        |           |
+------------------+                              |           |
|  TRANSACTIONS    |       +---------------------+           |
+------------------+       |                       |           |
| PK transactions_id|<-----+                       |           |
| FK users_id      |  1:N                         |           |
|     total_amount |       +---------------------+           |
| ENUM payment     |       |TRANSACTION_DETAILS   |           |
|     amount_paid |       +---------------------+           |
|     change_amt  |       | PK details_id        |           |
+------------------+       | FK transactions_id   |           |
        |                  | FK products_id      |           |
        | 1:N              |     quantity        |           |
        v                  |     price           |           |
+------------------+       |     subtotal        |           |
|  STOCK_HISTORY  |       +---------------------+           |
+------------------+                                         |
| PK history_id   |<----------------------------------------+
| FK products_id  |  (untuk track perubahan stok via sale)
| ENUM change_type|
|     qty_change |
| FK changed_by   |
+------------------+

ACCESS CONTROL:
┌────────────────────────────────────────────────────────────────┐
│                        ROLE: admin                             │
├────────────────────────────────────────────────────────────────┤
│  ✅ CREATE/UPDATE/DELETE products                             │
│  ✅ CREATE/UPDATE/DELETE categories                            │
│  ✅ RESTOCK inventory (tambah stok)                          │
│  ✅ ADJUST inventory (koreksi stok)                           │
│  ✅ VIEW all transactions & reports                            │
│  ✅ PROCESS transactions (POS)                                 │
│  ✅ CRUD users (jika ada fitur manajemen user)                │
├────────────────────────────────────────────────────────────────┤
│                        ROLE: kasir                             │
├────────────────────────────────────────────────────────────────┤
│  ❌ CRUD products (tidak punya akses)                         │
│  ❌ CRUD categories (tidak punya akses)                       │
│  ❌ RESTOCK/ADJUST inventory (tidak punya akses)             │
│  ❌ VIEW reports (tidak punya akses)                           │
│  ✅ PROCESS transactions (POS) - hanya bisa ini                │
└────────────────────────────────────────────────────────────────┘
*/

-- =====================================================
-- END OF DDL v2.0
-- =====================================================
