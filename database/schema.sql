-- inPOS Database Schema
-- Sistem POS & Inventaris Bahan Baku

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+07:00";

-- Table: users (Admin & Kasir)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: categories (Kategori Produk)
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: products (Data Produk)
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `category_id` INT UNSIGNED DEFAULT NULL,
    `price` DECIMAL(12,0) NOT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `min_stock` INT NOT NULL DEFAULT 5,
    `unit` VARCHAR(20) DEFAULT 'pcs',
    `barcode` VARCHAR(50) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `category_id` (`category_id`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: transactions (Header Transaksi)
CREATE TABLE IF NOT EXISTS `transactions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NOT NULL,
    `total_amount` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `payment_method` ENUM('cash', 'debit', 'qris') DEFAULT 'cash',
    `amount_paid` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `change_amount` DECIMAL(14,0) NOT NULL DEFAULT 0,
    `transaction_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    CONSTRAINT `fk_transactions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: transaction_details (Detail Per Item)
CREATE TABLE IF NOT EXISTS `transaction_details` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transaction_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(12,0) NOT NULL,
    `subtotal` DECIMAL(14,0) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `transaction_id` (`transaction_id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `fk_details_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_details_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: stock_history (Riwayat Perubahan Stok)
CREATE TABLE IF NOT EXISTS `stock_history` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` INT UNSIGNED NOT NULL,
    `change_type` ENUM('sale', 'restock', 'adjustment') NOT NULL,
    `quantity_change` INT NOT NULL,
    `stock_before` INT NOT NULL,
    `stock_after` INT NOT NULL,
    `reference_id` INT UNSIGNED DEFAULT NULL,
    `note` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert default admin account (password: admin123)
INSERT INTO `users` (`username`, `password`, `full_name`, `role`) VALUES
('admin', '$2a$10$FtRol/FHoLhaJAognM/OeePUGUvGMP8kiXZM85k76AlIBZjMYulu6', 'Administrator', 'admin'),
('kasir', '$2a$10$FtRol/FHoLhaJAognM/OeePUGUvGMP8kiXZM85k76AlIBZjMYulu6', 'Kasir Default', 'kasir');

-- Insert default categories
INSERT INTO `categories` (`name`, `description`) VALUES
('Makanan', 'Produk makanan'),
('Minuman', 'Produk minuman'),
('Snack', 'Produk camilan'),
('Lainnya', 'Produk lainnya');

-- Insert sample products
INSERT INTO `products` (`name`, `category_id`, `price`, `stock`, `min_stock`, `unit`) VALUES
('Nasi Goreng', 1, 25000, 50, 10, 'porsi'),
('Mie Goreng', 1, 20000, 45, 10, 'porsi'),
('Ayam Geprek', 1, 22000, 40, 10, 'porsi'),
('Es Teh Manis', 2, 5000, 100, 20, 'gelas'),
('Es Jeruk', 2, 6000, 80, 20, 'gelas'),
('Kopi Hitam', 2, 8000, 60, 15, 'cangkir'),
('Pisang Goreng', 3, 10000, 35, 10, 'porsi'),
('Tahu Crispy', 3, 8000, 40, 10, 'porsi');