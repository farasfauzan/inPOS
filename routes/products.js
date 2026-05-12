const express = require('express');
const router = express.Router();

module.exports = (db) => {
    // GET /api/products - Ambil semua produk
    router.get('/', async (req, res) => {
        try {
            const [products] = await db.query(`
                SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.categories_id = c.categories_id
                ORDER BY p.created_at DESC
            `);
            res.json(products);
        } catch (error) {
            console.error('Error fetching products:', error);
            res.status(500).json({ error: 'Gagal mengambil data produk' });
        }
    });

    // GET /api/products/:id
    router.get('/:id', async (req, res) => {
        try {
            const [products] = await db.query(
                'SELECT * FROM products WHERE products_id = ?',
                [req.params.id]
            );
            if (products.length === 0) {
                return res.status(404).json({ error: 'Produk tidak ditemukan' });
            }
            res.json(products[0]);
        } catch (error) {
            console.error('Error fetching product:', error);
            res.status(500).json({ error: 'Gagal mengambil data produk' });
        }
    });

    // POST /api/products - Tambah produk baru
    router.post('/', async (req, res) => {
        try {
            const { name, categories_id, price, stock, min_stock, unit, barcode } = req.body;

            if (!name || !price) {
                return res.status(400).json({ error: 'Nama dan harga wajib diisi' });
            }

            const [result] = await db.query(
                `INSERT INTO products (name, categories_id, price, stock, min_stock, unit, barcode)
                 VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [name, categories_id || null, price, stock || 0, min_stock || 5, unit || 'pcs', barcode || null]
            );

            const [newProduct] = await db.query('SELECT * FROM products WHERE products_id = ?', [result.insertId]);
            res.status(201).json({ message: 'Produk berhasil ditambahkan', product: newProduct[0] });
        } catch (error) {
            console.error('Error adding product:', error);
            res.status(500).json({ error: 'Gagal menambahkan produk' });
        }
    });

    // PUT /api/products/:id - Update produk
    router.put('/:id', async (req, res) => {
        try {
            const { name, categories_id, price, stock, min_stock, unit, barcode } = req.body;

            const [existing] = await db.query('SELECT products_id FROM products WHERE products_id = ?', [req.params.id]);
            if (existing.length === 0) {
                return res.status(404).json({ error: 'Produk tidak ditemukan' });
            }

            await db.query(
                `UPDATE products SET name = ?, categories_id = ?, price = ?, stock = ?, min_stock = ?, unit = ?, barcode = ? WHERE products_id = ?`,
                [name, categories_id || null, price, stock, min_stock || 5, unit || 'pcs', barcode || null, req.params.id]
            );

            const [updated] = await db.query('SELECT * FROM products WHERE products_id = ?', [req.params.id]);
            res.json({ message: 'Produk berhasil diperbarui', product: updated[0] });
        } catch (error) {
            console.error('Error updating product:', error);
            res.status(500).json({ error: 'Gagal memperbarui produk' });
        }
    });

    // DELETE /api/products/:id
    router.delete('/:id', async (req, res) => {
        try {
            const [existing] = await db.query('SELECT products_id FROM products WHERE products_id = ?', [req.params.id]);
            if (existing.length === 0) {
                return res.status(404).json({ error: 'Produk tidak ditemukan' });
            }

            await db.query('DELETE FROM products WHERE products_id = ?', [req.params.id]);
            res.json({ message: 'Produk berhasil dihapus' });
        } catch (error) {
            console.error('Error deleting product:', error);
            res.status(500).json({ error: 'Gagal menghapus produk' });
        }
    });

    // GET /api/products/search/query - Cari produk
    router.get('/search/:query', async (req, res) => {
        try {
            const query = `%${req.params.query}%`;
            const [products] = await db.query(`
                SELECT p.*, c.name as category_name
                FROM products p
                LEFT JOIN categories c ON p.categories_id = c.categories_id
                WHERE p.name LIKE ? OR p.barcode LIKE ?
                ORDER BY p.name
            `, [query, query]);
            res.json(products);
        } catch (error) {
            console.error('Error searching products:', error);
            res.status(500).json({ error: 'Gagal mencari produk' });
        }
    });

    return router;
};
