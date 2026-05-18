-- =====================================================
-- Sample Data for inPOS-Laravel Database
-- Database: MySQL
-- =====================================================

-- =====================================================
-- INSERT: users
-- Note: Password is bcrypt hashed version of 'admin123' and 'kasir123'
-- =====================================================
INSERT INTO users (username, password, full_name, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('kasir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kasir', 'kasir');

-- =====================================================
-- INSERT: categories
-- =====================================================
INSERT INTO categories (name, description) VALUES
('Makanan', 'Kategori untuk makanan'),
('Minuman', 'Kategori untuk minuman'),
('Snack', 'Kategori untuk makanan ringan');

-- =====================================================
-- INSERT: products
-- =====================================================
INSERT INTO products (name, categories_id, price, stock, min_stock, unit) VALUES
('Nasi Goreng', 1, 18000, 50, 10, 'porsi'),
('Mie Goreng', 1, 15000, 45, 10, 'porsi'),
('Ayam Geprek', 1, 20000, 40, 10, 'porsi'),
('Es Teh Manis', 2, 5000, 100, 20, 'gelas'),
('Es Jeruk', 2, 6000, 80, 20, 'gelas'),
('Kopi Hitam', 2, 8000, 60, 15, 'gelas'),
('Pisang Goreng', 3, 10000, 35, 10, 'porsi'),
('Tahu Crispy', 3, 8000, 3, 10, 'porsi');

-- =====================================================
-- END OF SAMPLE DATA
-- =====================================================
