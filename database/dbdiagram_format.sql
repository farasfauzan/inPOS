-- ============================================
-- inPOS Database Design for SQL Data Modeler
-- dbdiagram.io Format Export
-- ============================================
-- Paste this code di https://dbdiagram.io/draw untuk generate ERD visual
-- ============================================

// Table: users
Table users as USERS {
  users_id int [pk, increment]
  username varchar(50) [unique, not null]
  password varchar(255) [not null]
  full_name varchar(100) [not null]
  role enum('admin', 'kasir') [not null, default: 'kasir']
  created_at timestamp [not null, default: 'current_timestamp']
  updated_at timestamp [not null, default: 'current_timestamp']
}

// Table: categories
Table categories as CATEGORIES {
  categories_id int [pk, increment]
  name varchar(100) [not null]
  description text
  created_at timestamp [not null, default: 'current_timestamp']
}

// Table: products
Table products as PRODUCTS {
  products_id int [pk, increment]
  name varchar(100) [not null]
  categories_id int [ref: > CATEGORIES.categories_id]
  price decimal(12,0) [not null]
  stock int [not null, default: 0]
  min_stock int [not null, default: 5]
  unit varchar(20) [default: 'pcs']
  barcode varchar(50) [unique]
  image_url varchar(255)
  created_at timestamp [not null, default: 'current_timestamp']
  updated_at timestamp [not null, default: 'current_timestamp']
}

// Table: transactions
Table transactions as TRANSACTIONS {
  transactions_id int [pk, increment]
  users_id int [not null, ref: > USERS.users_id]
  total_amount decimal(14,0) [not null, default: 0]
  payment_method enum('cash', 'debit', 'qris') [default: 'cash']
  amount_paid decimal(14,0) [not null, default: 0]
  change_amount decimal(14,0) [not null, default: 0]
  transaction_date timestamp [not null, default: 'current_timestamp']
}

// Table: transaction_details
Table transaction_details as TRANSACTION_DETAILS {
  transaction_details_id int [pk, increment]
  transactions_id int [not null, ref: > TRANSACTIONS.transactions_id]
  products_id int [not null, ref: > PRODUCTS.products_id]
  quantity int [not null]
  price decimal(12,0) [not null]
  subtotal decimal(14,0) [not null]
}

// Table: stock_history
Table stock_history as STOCK_HISTORY {
  stock_history_id int [pk, increment]
  products_id int [not null, ref: > PRODUCTS.products_id]
  change_type enum('sale', 'restock', 'adjustment') [not null]
  quantity_change int [not null]
  stock_before int [not null]
  stock_after int [not null]
  reference_id int
  note varchar(255)
  created_at timestamp [not null, default: 'current_timestamp']
}

// ============================================
// ERD RELATIONSHIPS
// ============================================
// Note: Foreign key relationships are already defined above

// USERS (1) ----< (N) TRANSACTIONS
// Kasir membuat banyak transaksi

// TRANSACTIONS (1) ----< (N) TRANSACTION_DETAILS
// 1 transaksi memiliki banyak item

// CATEGORIES (1) ----< (N) PRODUCTS
// 1 kategori memiliki banyak produk

// PRODUCTS (1) ----< (N) TRANSACTION_DETAILS
// 1 produk bisa dijual banyak kali

// PRODUCTS (1) ----< (N) STOCK_HISTORY
// 1 produk memiliki banyak riwayat stok

// ============================================
// INDEXES
// ============================================
CREATE INDEX idx_products_category ON PRODUCTS (categories_id);
CREATE INDEX idx_products_name ON PRODUCTS (name);
CREATE INDEX idx_transactions_user ON TRANSACTIONS (users_id);
CREATE INDEX idx_transactions_date ON TRANSACTIONS (transaction_date);
CREATE INDEX idx_details_transaction ON TRANSACTION_DETAILS (transactions_id);
CREATE INDEX idx_details_product ON TRANSACTION_DETAILS (products_id);
CREATE INDEX idx_history_product ON STOCK_HISTORY (products_id);
CREATE INDEX idx_history_date ON STOCK_HISTORY (created_at);
