-- ============================================
-- inPOS Database Design
-- SQL Data Modeler DDL
-- Sistem POS & Inventaris Bahan Baku
-- ============================================

-- ============================================
-- 1. USERS (Admin & Kasir)
-- ============================================
CREATE TABLE users (
    users_id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    username             VARCHAR(50)     NOT NULL,
    password             VARCHAR(255)    NOT NULL,
    full_name            VARCHAR(100)    NOT NULL,
    role                 ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
    created_at          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    --
    CONSTRAINT pk_users PRIMARY KEY (users_id),
    CONSTRAINT uk_users_username UNIQUE (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMENT ON TABLE users IS 'Tabel user untuk autentikasi admin dan kasir';
COMMENT ON COLUMN users.users_id IS 'Primary key tabel users';
COMMENT ON COLUMN users.username IS 'Username unik untuk login';
COMMENT ON COLUMN users.password IS 'Password terenkripsi bcrypt';
COMMENT ON COLUMN users.full_name IS 'Nama lengkap user';
COMMENT ON COLUMN users.role IS 'Role user: admin atau kasir';


-- ============================================
-- 2. CATEGORIES (Kategori Produk)
-- ============================================
CREATE TABLE categories (
    categories_id        INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name                VARCHAR(100)    NOT NULL,
    description         TEXT,
    created_at          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    --
    CONSTRAINT pk_categories PRIMARY KEY (categories_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMENT ON TABLE categories IS 'Tabel kategori untuk mengelompokkan produk';
COMMENT ON COLUMN categories.categories_id IS 'Primary key tabel kategori';
COMMENT ON COLUMN categories.name IS 'Nama kategori produk';
COMMENT ON COLUMN categories.description IS 'Deskripsi detail kategori';


-- ============================================
-- 3. PRODUCTS (Data Produk)
-- ============================================
CREATE TABLE products (
    products_id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    name                VARCHAR(100)    NOT NULL,
    categories_id       INT UNSIGNED    NULL,
    price               DECIMAL(12,0)   NOT NULL,
    stock               INT            NOT NULL DEFAULT 0,
    min_stock           INT            NOT NULL DEFAULT 5,
    unit                VARCHAR(20)    DEFAULT 'pcs',
    barcode             VARCHAR(50)    NULL,
    image_url           VARCHAR(255)    NULL,
    created_at          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    --
    CONSTRAINT pk_products PRIMARY KEY (products_id),
    CONSTRAINT fk_products_categories
        FOREIGN KEY (categories_id) REFERENCES categories (categories_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT uk_products_barcode UNIQUE (barcode)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMENT ON TABLE products IS 'Tabel data produk/jualan';
COMMENT ON COLUMN products.products_id IS 'Primary key tabel produk';
COMMENT ON COLUMN products.name IS 'Nama produk';
COMMENT ON COLUMN products.categories_id IS 'Foreign key ke tabel categories';
COMMENT ON COLUMN products.price IS 'Harga jual produk';
COMMENT ON COLUMN products.stock IS 'Stok saat ini';
COMMENT ON COLUMN products.min_stock IS 'Batas minimum stok untuk warning';
COMMENT ON COLUMN products.unit IS 'Satuan produk (pcs, porsi, gelas, dll)';
COMMENT ON COLUMN products.barcode IS 'Kode barcode produk (unique)';
COMMENT ON COLUMN products.image_url IS 'URL gambar produk';

CREATE INDEX idx_products_category ON products (categories_id);
CREATE INDEX idx_products_name ON products (name);


-- ============================================
-- 4. TRANSACTIONS (Header Transaksi Penjualan)
-- ============================================
CREATE TABLE transactions (
    transactions_id           INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    users_id                INT UNSIGNED    NOT NULL,
    total_amount            DECIMAL(14,0)   NOT NULL DEFAULT 0,
    payment_method          ENUM('cash', 'debit', 'qris') DEFAULT 'cash',
    amount_paid             DECIMAL(14,0)   NOT NULL DEFAULT 0,
    change_amount           DECIMAL(14,0)   NOT NULL DEFAULT 0,
    transaction_date       TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    --
    CONSTRAINT pk_transactions PRIMARY KEY (transactions_id),
    CONSTRAINT fk_transactions_users
        FOREIGN KEY (users_id) REFERENCES users (users_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMENT ON TABLE transactions IS 'Tabel header transaksi penjualan';
COMMENT ON COLUMN transactions.transactions_id IS 'Primary key transaksi';
COMMENT ON COLUMN transactions.users_id IS 'Foreign key ke tabel users (kasir)';
COMMENT ON COLUMN transactions.total_amount IS 'Total jumlah transaksi';
COMMENT ON COLUMN transactions.payment_method IS 'Metode pembayaran: cash, debit, qris';
COMMENT ON COLUMN transactions.amount_paid IS 'Jumlah uang yang dibayarkan';
COMMENT ON COLUMN transactions.change_amount IS 'Jumlah kembalian';
COMMENT ON COLUMN transactions.transaction_date IS 'Tanggal dan waktu transaksi';

CREATE INDEX idx_transactions_user ON transactions (users_id);
CREATE INDEX idx_transactions_date ON transactions (transaction_date);


-- ============================================
-- 5. TRANSACTION_DETAILS (Detail Item Transaksi)
-- ============================================
CREATE TABLE transaction_details (
    transaction_details_id   INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    transactions_id         INT UNSIGNED    NOT NULL,
    products_id             INT UNSIGNED    NOT NULL,
    quantity               INT            NOT NULL,
    price                  DECIMAL(12,0)   NOT NULL,
    subtotal               DECIMAL(14,0)   NOT NULL,
    --
    CONSTRAINT pk_transaction_details PRIMARY KEY (transaction_details_id),
    CONSTRAINT fk_details_transactions
        FOREIGN KEY (transactions_id) REFERENCES transactions (transactions_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_details_products
        FOREIGN KEY (products_id) REFERENCES products (products_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMENT ON TABLE transaction_details IS 'Tabel detail item per transaksi';
COMMENT ON COLUMN transaction_details.transaction_details_id IS 'Primary key detail transaksi';
COMMENT ON COLUMN transaction_details.transactions_id IS 'Foreign key ke tabel transactions';
COMMENT ON COLUMN transaction_details.products_id IS 'Foreign key ke tabel products';
COMMENT ON COLUMN transaction_details.quantity IS 'Jumlah item yang dibeli';
COMMENT ON COLUMN transaction_details.price IS 'Harga satuan saat transaksi';
COMMENT ON COLUMN transaction_details.subtotal IS 'Subtotal (quantity * price)';

CREATE INDEX idx_details_transaction ON transaction_details (transactions_id);
CREATE INDEX idx_details_product ON transaction_details (products_id);


-- ============================================
-- 6. STOCK_HISTORY (Riwayat Perubahan Stok)
-- ============================================
CREATE TABLE stock_history (
    stock_history_id         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    products_id             INT UNSIGNED    NOT NULL,
    change_type            ENUM('sale', 'restock', 'adjustment') NOT NULL,
    quantity_change        INT            NOT NULL,
    stock_before          INT            NOT NULL,
    stock_after            INT            NOT NULL,
    reference_id           INT UNSIGNED    NULL,
    note                   VARCHAR(255)   NULL,
    created_at              TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    --
    CONSTRAINT pk_stock_history PRIMARY KEY (stock_history_id),
    CONSTRAINT fk_history_products
        FOREIGN KEY (products_id) REFERENCES products (products_id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMENT ON TABLE stock_history IS 'Tabel riwayat perubahan stok produk';
COMMENT ON COLUMN stock_history.stock_history_id IS 'Primary key history';
COMMENT ON COLUMN stock_history.products_id IS 'Foreign key ke tabel products';
COMMENT ON COLUMN stock_history.change_type IS 'Tipe perubahan: sale, restock, adjustment';
COMMENT ON COLUMN stock_history.quantity_change IS 'Jumlah perubahan (+/-)';
COMMENT ON COLUMN stock_history.stock_before IS 'Stok sebelum perubahan';
COMMENT ON COLUMN stock_history.stock_after IS 'Stok setelah perubahan';
COMMENT ON COLUMN stock_history.reference_id IS 'ID reference (transactions_id jika sale)';
COMMENT ON COLUMN stock_history.note IS 'Catatan tambahan';
COMMENT ON COLUMN stock_history.created_at IS 'Waktu perubahan';

CREATE INDEX idx_history_product ON stock_history (products_id);
CREATE INDEX idx_history_date ON stock_history (created_at);


-- ============================================
-- DML: Default Data
-- ============================================

-- Insert default users (password: admin123)
INSERT INTO users (username, password, full_name, role) VALUES
('admin', '$2a$10$FtRol/FHoLhaJAognM/OeePUGUvGMP8kiXZM85k76AlIBZjMYulu6', 'Administrator', 'admin'),
('kasir', '$2a$10$FtRol/FHoLhaJAognM/OeePUGUvGMP8kiXZM85k76AlIBZjMYulu6', 'Kasir Default', 'kasir');

-- Insert default categories
INSERT INTO categories (name, description) VALUES
('Makanan', 'Produk makanan utama'),
('Minuman', 'Produk minuman'),
('Snack', 'Produk camilan'),
('Lainnya', 'Produk lainnya');

-- Insert sample products
INSERT INTO products (name, categories_id, price, stock, min_stock, unit) VALUES
('Nasi Goreng', 1, 25000, 50, 10, 'porsi'),
('Mie Goreng', 1, 20000, 45, 10, 'porsi'),
('Ayam Geprek', 1, 22000, 40, 10, 'porsi'),
('Es Teh Manis', 2, 5000, 100, 20, 'gelas'),
('Es Jeruk', 2, 6000, 80, 20, 'gelas'),
('Kopi Hitam', 2, 8000, 60, 15, 'cangkir'),
('Pisang Goreng', 3, 10000, 35, 10, 'porsi'),
('Tahu Crispy', 3, 8000, 40, 10, 'porsi');


-- ============================================
-- ERD RELATIONSHIP SUMMARY
-- ============================================
-- USERS (1,N) --------< TRANSACTIONS (N,1)
-- USERS (1,1)         PK:users_id
-- PK:users_id         FK:users_id
--
-- TRANSACTIONS (1,N) --------< TRANSACTION_DETAILS (N,1)
-- PK:transactions_id   PK:transaction_details_id
-- FK:users_id         FK:transactions_id
--
-- CATEGORIES (1,N) ----< PRODUCTS (N,1)
-- PK:categories_id    PK:products_id
--                     FK:categories_id
--
-- PRODUCTS (1,N) ------< TRANSACTION_DETAILS (N,1)
-- PK:products_id      PK:transaction_details_id
--                     FK:products_id
--
-- PRODUCTS (1,N) ------< STOCK_HISTORY (N,1)
-- PK:products_id      PK:stock_history_id
--                     FK:products_id
