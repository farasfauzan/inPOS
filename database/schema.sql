-- ============================================
-- inPOS Database Schema
-- Sistem POS & Inventaris Bahan Baku
-- ============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- ============================================
-- Table: users (Admin & Kasir)
-- ============================================
CREATE TABLE IF NOT EXISTS `users` (
    `users_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`users_id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Table: categories (Kategori Produk)
-- ============================================
CREATE TABLE IF NOT EXISTS `categories` (
    `categories_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Table: products (Data Produk)
-- ============================================
CREATE TABLE IF NOT EXISTS `products` (
    `products_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `categories_id` INT UNSIGNED DEFAULT NULL,
    `price` DECIMAL(12,0) NOT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `min_stock` INT NOT NULL DEFAULT 5,
    `unit` VARCHAR(20) DEFAULT 'pcs',
    `barcode` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`products_id`),
    KEY `categories_id` (`categories_id`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`categories_id`) REFERENCES `categories` (`categories_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Table: transactions (Header Transaksi)
-- ============================================
CREATE TABLE IF NOT EXISTS `transactions` (
    `transactions_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `users_id` INT UNSIGNED NOT NULL,
    `total_amount` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `payment_method` ENUM('cash', 'debit', 'qris') DEFAULT 'cash',
    `amount_paid` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `change_amount` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `transaction_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`transactions_id`),
    KEY `users_id` (`users_id`),
    CONSTRAINT `fk_transactions_user` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Table: transaction_details (Detail Per Item)
-- ============================================
CREATE TABLE IF NOT EXISTS `transaction_details` (
    `transaction_details_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transactions_id` INT UNSIGNED NOT NULL,
    `products_id` INT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(12,0) NOT NULL,
    `subtotal` DECIMAL(14,0) NOT NULL,
    PRIMARY KEY (`transaction_details_id`),
    KEY `transactions_id` (`transactions_id`),
    KEY `products_id` (`products_id`),
    CONSTRAINT `fk_details_transaction` FOREIGN KEY (`transactions_id`) REFERENCES `transactions` (`transactions_id`) ON DELETE CASCADE,
    CONSTRAINT `fk_details_product` FOREIGN KEY (`products_id`) REFERENCES `products` (`products_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Table: stock_history (Riwayat Perubahan Stok)
-- ============================================
CREATE TABLE IF NOT EXISTS `stock_history` (
    `stock_history_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `products_id` INT UNSIGNED NOT NULL,
    `change_type` ENUM('sale', 'restock', 'adjustment') NOT NULL,
    `quantity_change` INT NOT NULL,
    `stock_before` INT NOT NULL,
    `stock_after` INT NOT NULL,
    `reference_id` INT UNSIGNED DEFAULT NULL,
    `note` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`stock_history_id`),
    KEY `products_id` (`products_id`),
    CONSTRAINT `fk_stock_product` FOREIGN KEY (`products_id`) REFERENCES `products` (`products_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- Insert default admin account (password: admin123)
-- ============================================
INSERT INTO `users` (`username`, `password`, `full_name`, `role`) VALUES
('admin', '$2a$10$FtRol/FHoLhaJAognM/OeePUGUvGMP8kiXZM85k76AlIBZjMYulu6', 'Administrator', 'admin'),
('kasir', '$2a$10$FtRol/FHoLhaJAognM/OeePUGUvGMP8kiXZM85k76AlIBZjMYulu6', 'Kasir Default', 'kasir');

-- ============================================
-- Insert default categories
-- ============================================
INSERT INTO `categories` (`name`, `description`) VALUES
('Makanan', 'Produk makanan'),
('Minuman', 'Produk minuman'),
('Snack', 'Produk camilan'),
('Lainnya', 'Produk lainnya');

-- ============================================
-- Insert sample products
-- ============================================
INSERT INTO `products` (`name`, `categories_id`, `price`, `stock`, `min_stock`, `unit`) VALUES
('Nasi Goreng', 1, 25000, 50, 10, 'porsi'),
('Mie Goreng', 1, 20000, 45, 10, 'porsi'),
('Ayam Geprek', 1, 22000, 40, 10, 'porsi'),
('Es Teh Manis', 2, 5000, 100, 20, 'gelas'),
('Es Jeruk', 2, 6000, 80, 20, 'gelas'),
('Kopi Hitam', 2, 8000, 60, 15, 'cangkir'),
('Pisang Goreng', 3, 10000, 35, 10, 'porsi'),
('Tahu Crispy', 3, 8000, 40, 10, 'porsi');
